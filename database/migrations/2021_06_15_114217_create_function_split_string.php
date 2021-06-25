<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFunctionSplitString extends Migration
{
    /**
     * Run the migrations.
     * TO CREATE FUNCTION SPLIT STRING TO FILTER HOBBY AND OCCUPATION 
     ACCORDING ON CUSTOMER TABLE.
     *
     * FUNCTION TAKEN FROM : https://stackoverflow.com/questions/5928599/equivalent-of-explode-to-work-with-strings-in-mysql
     *
     * @return void
     */
    public function up()
    {
        $sql = "DROP FUNCTION IF EXISTS SPLIT_STRING;
                CREATE FUNCTION SPLIT_STRING(str VARCHAR(255), delim VARCHAR(12), pos INT)
                RETURNS VARCHAR(255)

                BEGIN
                  RETURN REPLACE(SUBSTRING(SUBSTRING_INDEX(str, delim, pos),
                       CHAR_LENGTH(SUBSTRING_INDEX(str, delim, pos-1)) + 1),
                       delim, '');
                END";
                
        DB::unprepared($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       DB::unprepared('DROP FUNCTION IF EXISTS SPLIT_STRING;');
    }
}
