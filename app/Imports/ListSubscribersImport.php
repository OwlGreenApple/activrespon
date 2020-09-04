<?php

namespace App\Imports;

use App\Customer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
// use App\Rules\ImportValidation;

use App\Http\Controllers\ListController;


class ListSubscribersImport implements ToCollection,WithStartRow 
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    use Importable;
    private $rows = 0;

    public function __construct(int $idlist)
    {
        $this->id_list = $idlist;
    }

    public function bindValue(PHPExcel_Cell $cell, $value = null)
    {
        if (is_numeric($value))
        {
            $cell->setValueExplicit($value, PHPExcel_Cell_DataType::TYPE_STRING);

            return true;
        }
        
        // else return default behavior
        return parent::bindValue($cell, $value);
    }
    
    public function collection(Collection $rows)
    { 
        $data = $rows->toArray();
        $cell = $rules_phone = $rules_username = array();
        $list_id = $this->id_list;

        if(count($data) > 0)
        {
            foreach($data as $index => $col)
            {
                $cell[] = array(
                    'name'=>$col[0],
                    'phone'=>ltrim($col[1]," "),
                    'email'=>$col[2],
                );
            }
        }

        $niceNames = array(
            '*.name' => 'name',
            '*.phone' => 'phone',
            '*.email' => 'email',
        );

        /*$messages = [
            'required_if'=>'The :attribute field is required when :other is blank'
        ];*/

        $rules = [
           '*.name'=> ['required'],
           '*.phone'=> ['required'],
           // '*.phone'=> ['required_if:*.username,==,'.null.'',new TelegramNumber],
           '*.email'=> ['email'],
           //'*.username'=> ['required_if:*.phone,==,'.null.''],
        ];

        $validator = Validator::make($cell,$rules); 
        $validator->setAttributeNames($niceNames);
        $validator->validate(); 

        if(count($rows) > 0)
        {
            foreach ($rows as $row) 
            {
                $row[1] = $this->checkPhone($row[1]);
                $checkunique = $this->checkUniquePhone($row[1],$row[2],$list_id);
                $checkuniqueemail = $this->checkUniqueEmail($row[2],$list_id);

                if($checkunique == true && $checkuniqueemail == true)
                {
                  ++$this->rows;
                  Customer::create([
                     'user_id'  => Auth::id(),
                     'list_id'  => $this->id_list,
                     'name'     => $row[0],
                     'telegram_number'=>ltrim($row[1]," "),
                     'email'=> $row[2],
                     'status'=> 1,
                  ]);
                }
            }
        }
    } 
    
    public function getRowCount(): int
    {
        return $this->rows;
    }

    public function startRow(): int
    {
        return 2;
    }

    public function checkPhone($row){
        if(preg_match('/^0[0-9]*$/i',$row))
        {
            $row = ltrim($row,0);
        } 

        if(preg_match('/^[+]0[0-9]*$/i',$row))
        {
            $row = ltrim($row,'+0');
        }

        if(empty($row)) 
        {
            $row = 0;
        }

        if(!preg_match('/^[+][0-9]*$/i',$row) && $row <> 0)
        {
            $row = '+'.$row;
        } 

        return $row;
    }

    private function checkUniquePhone($number,$list_id)
    {
        $userid = Auth::id();
        $customer = Customer::where([['user_id',$userid],['list_id',$list_id],['telegram_number','=',$number]])->first();
        if(is_null($customer))
        {
           return true;
        }
        else {
           return false;
        }
    }
    
    /*public function checkUniqueEmail($email,$list_id){
        $email = Customer::where([['email','=',$email],['list_id',$list_id],])->first();
        if(is_null($email))
        {
           return true;
        }
        else {
           return false;
        }
    }*/

/* end class */    
}
