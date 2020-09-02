<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('countries')->insert([
          'name' => 'Afghanistan',
          'code' => '93',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);   
 
      DB::table('countries')->insert([
          'name' => 'Albania',
          'code' => '355',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);   

      DB::table('countries')->insert([
          'name' => 'Algeria',
          'code' => '213',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'American Samoa',
          'code' => '1-684',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);   

      DB::table('countries')->insert([
          'name' => 'Andorra',
          'code' => '376',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);  

      DB::table('countries')->insert([
          'name' => 'Angola',
          'code' => '244',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'Anguilla',
          'code' => '1-264',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'Antarctica',
          'code' => '672',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'Antigua & Barbuda ',
          'code' => '1-268',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'Argentina',
          'code' => '54',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'Armenia',
          'code' => '374',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);  

      DB::table('countries')->insert([
          'name' => 'Aruba',
          'code' => '297',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);  

      DB::table('countries')->insert([
          'name' => 'Australia',
          'code' => '61',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'Austria',
          'code' => '43',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'Azerbaijan',
          'code' => '994',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'Bahamas',
          'code' => '1-242',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'Bahrain',
          'code' => '973',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'Bangladesh',
          'code' => '880',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'Barbados',
          'code' => '1-246',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'Belarus',
          'code' => '375',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'Belgium',
          'code' => '32',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'Belize',
          'code' => '501',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'Benin',
          'code' => '229',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'Bermuda',
          'code' => '1-441',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'Bhutan',
          'code' => '975',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'Bolivia',
          'code' => '591',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'Bosnia Herzegovina',
          'code' => '387',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'Botswana',
          'code' => '267',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'Brazil',
          'code' => '55',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'British Indian Ocean Territory',
          'code' => '246',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'British Virgin Islands',
          'code' => '1-284',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'Brunei',
          'code' => '673',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'Bulgaria',
          'code' => '359',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'Burkina Faso',
          'code' => '226',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'Burundi',
          'code' => '257',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'Cambodia',
          'code' => '855',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'Cameroon',
          'code' => '237',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'Canada',
          'code' => '1',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'Cape Verde Islands',
          'code' => '238',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'Cayman Islands',
          'code' => '1-345',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'Central African Republic',
          'code' => '236',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);  

      DB::table('countries')->insert([
          'name' => 'Chad',
          'code' => '235',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'Chile',
          'code' => '56',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'China',
          'code' => '86',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'Christmas Island',
          'code' => '61',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Cocos Islands',
          'code' => '61',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Colombia',
          'code' => '57',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'Comoros',
          'code' => '269',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Cook Islands',
          'code' => '682',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'Costa Rica',
          'code' => '506',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Croatia',
          'code' => '385',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'Cuba',
          'code' => '53',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'Cyprus',
          'code' => '357',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'Czech Republic',
          'code' => '420',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Democratic Republic of the Congo',
          'code' => '243',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'Denmark',
          'code' => '45',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);   

      DB::table('countries')->insert([
          'name' => 'Djibouti',
          'code' => '253',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);   

      DB::table('countries')->insert([
          'name' => 'Dominica',
          'code' => '1-767',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Dominican Republic',
          'code' => '1-809',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'East Timor',
          'code' => '670',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Ecuador',
          'code' => '593',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Egypt',
          'code' => '20',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'El Salvador',
          'code' => '503',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Equatorial Guinea',
          'code' => '240',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Eritrea',
          'code' => '291',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Estonia',
          'code' => '372',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Ethiopia',
          'code' => '251',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Falkland Islands',
          'code' => '500',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Faroe Islands',
          'code' => '298',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Fiji',
          'code' => '679',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Finland',
          'code' => '358',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'France',
          'code' => '33',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'French Polynesia',
          'code' => '689',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Gabon',
          'code' => '241',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Gambia',
          'code' => '220',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Georgia',
          'code' => '995',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Germany',
          'code' => '49',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Ghana',
          'code' => '233',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Gibraltar',
          'code' => '350',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Greece',
          'code' => '30',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Greenland',
          'code' => '299',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Grenada',
          'code' => '1-473',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Guam',
          'code' => '1-671',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Guatemala',
          'code' => '502',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Guernsey',
          'code' => '44-1481',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Guinea',
          'code' => '224',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Guinea-Bissau',
          'code' => '245',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Guyana',
          'code' => '592',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Haiti',
          'code' => '509',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Honduras',
          'code' => '504',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Hong Kong',
          'code' => '852',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Hungary',
          'code' => '36',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Iceland',
          'code' => '354',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'India',
          'code' => '91',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Indonesia',
          'code' => '62',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Iran',
          'code' => '98',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Iraq',
          'code' => '964',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Ireland',
          'code' => '353',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Isle of Man',
          'code' => '44-1624',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Israel',
          'code' => '972',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Italy',
          'code' => '39',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Ivory Coast',
          'code' => '225',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Jamaica',
          'code' => '1-876',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Japan',
          'code' => '81',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Jersey',
          'code' => '44-1534',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Jordan',
          'code' => '962',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Kazakhstan',
          'code' => '7',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Kenya',
          'code' => '254',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Kiribati',
          'code' => '686',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Kosovo',
          'code' => '383',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Kuwait',
          'code' => '965',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Kyrgyzstan',
          'code' => '996',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Laos',
          'code' => '856',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Latvia',
          'code' => '371',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Lebanon',
          'code' => '961',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Lesotho',
          'code' => '266',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Liberia',
          'code' => '231',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Libya',
          'code' => '218',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Liechtenstein',
          'code' => '423',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Lithuania',
          'code' => '370',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Luxembourg',
          'code' => '352',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Macau',
          'code' => '853',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Macedonia',
          'code' => '389',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Madagascar',
          'code' => '261',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Malawi',
          'code' => '265',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Malaysia',
          'code' => '60',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Maldives',
          'code' => '960',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Mali',
          'code' => '223',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Malta',
          'code' => '356',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Marshall Islands',
          'code' => '692',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Mauritania',
          'code' => '222',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Mauritius',
          'code' => '230',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Mayotte',
          'code' => '262',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Mexico',
          'code' => '52',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Micronesia',
          'code' => '691',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Moldova',
          'code' => '373',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Monaco',
          'code' => '377',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Mongolia',
          'code' => '976',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Montenegro',
          'code' => '382',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Montserrat',
          'code' => '1-664',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Morocco',
          'code' => '212',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Mozambique',
          'code' => '258',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Myanmar',
          'code' => '95',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Namibia',
          'code' => '264',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Nauru',
          'code' => '674',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Nepal',
          'code' => '977',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Netherlands',
          'code' => '31',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Netherlands Antilles',
          'code' => '599',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'New Caledonia',
          'code' => '687',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'New Zealand',
          'code' => '64',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Nicaragua',
          'code' => '505',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Niger',
          'code' => '227',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Nigeria',
          'code' => '234',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Niue',
          'code' => '683',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'North Korea',
          'code' => '850',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Northern Mariana Islands',
          'code' => '1-670',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Norway',
          'code' => '47',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Oman',
          'code' => '968',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Pakistan',
          'code' => '92',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Palau',
          'code' => '680',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Palestine',
          'code' => '970',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Panama',
          'code' => '507',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Papua New Guinea',
          'code' => '675',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Paraguay',
          'code' => '595',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Peru',
          'code' => '51',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Philippines',
          'code' => '63',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Pitcairn',
          'code' => '64',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Poland',
          'code' => '48',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Portugal',
          'code' => '351',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Puerto Rico',
          'code' => '1-787',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]); 

      DB::table('countries')->insert([
          'name' => 'Qatar',
          'code' => '974',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Republic of the Congo',
          'code' => '242',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Reunion',
          'code' => '262',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Romania',
          'code' => '40',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Russia',
          'code' => '7',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Rwanda',
          'code' => '250',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Saint Barthelemy',
          'code' => '590',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Saint Helena',
          'code' => '290',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Saint Kitts and Nevis',
          'code' => '1-869',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Saint Lucia',
          'code' => '1-758',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Saint Martin',
          'code' => '590',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Saint Pierre and Miquelon',
          'code' => '508',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Saint Vincent and the Grenadines',
          'code' => '1-784',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Samoa',
          'code' => '685',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'San Marino',
          'code' => '378',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Sao Tome and Principe',
          'code' => '239',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Saudi Arabia',
          'code' => '966',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Senegal',
          'code' => '221',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Serbia',
          'code' => '381',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Seychelles',
          'code' => '248',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Sierra Leone',
          'code' => '232',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Singapore',
          'code' => '65',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Sint Maarten',
          'code' => '1-721',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Slovakia',
          'code' => '421',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Slovenia',
          'code' => '386',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Solomon Islands',
          'code' => '677',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Somalia',
          'code' => '252',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'South Africa',
          'code' => '27',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'South Korea',
          'code' => '82',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'South Sudan',
          'code' => '211',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Spain',
          'code' => '34',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Sri Lanka',
          'code' => '94',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Sudan',
          'code' => '249',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Suriname',
          'code' => '597',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Svalbard and Jan Mayen',
          'code' => '47',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Swaziland',
          'code' => '268',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Sweden',
          'code' => '46',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Switzerland',
          'code' => '41',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Syria',
          'code' => '963',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Taiwan',
          'code' => '886',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Tajikistan',
          'code' => '992',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Tanzania',
          'code' => '255',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Thailand',
          'code' => '66',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Togo',
          'code' => '228',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Tokelau',
          'code' => '690',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Tonga',
          'code' => '676',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Trinidad and Tobago',
          'code' => '1-868',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Tunisia',
          'code' => '216',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Turkey',
          'code' => '90',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Turkmenistan',
          'code' => '993',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Turks and Caicos Islands',
          'code' => '1-649',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Tuvalu',
          'code' => '688',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'U.S. Virgin Islands',
          'code' => '1-340',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Tuvalu',
          'code' => '688',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Uganda',
          'code' => '256',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Ukraine',
          'code' => '380',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'United Arab Emirates',
          'code' => '971',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'United Kingdom',
          'code' => '44',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'United States',
          'code' => '1',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Uruguay',
          'code' => '598',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Uzbekistan',
          'code' => '998',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Vanuatu',
          'code' => '678',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Vatican',
          'code' => '379',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Venezuela',
          'code' => '58',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Vietnam',
          'code' => '84',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Wallis and Futuna',
          'code' => '681',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Western Sahara',
          'code' => '212',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Yemen',
          'code' => '967',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Zambia',
          'code' => '260',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

      DB::table('countries')->insert([
          'name' => 'Zimbabwe',
          'code' => '263',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now()
      ]);

    }
    
/* Source : https://countrycode.org/ */
/*End Class*/
}
