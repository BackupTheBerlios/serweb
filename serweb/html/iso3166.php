<?php
/**
 *	Classes holding country codes by the ISO3166
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: iso3166.php,v 1.2 2007/02/14 16:36:39 kozlik Exp $
 *	@package    serweb
 */ 

/**
 *	Classes holding country codes by the ISO3166
 * 
 *	@package    serweb
 */ 
class ISO3166{
	function get_country_codes(){
		return array(
				"AF",  //Afgh�nist�n
				"AL",  //Alb�nie
				"DZ",  //Al��rsko
				"AS",  //Americk� Samoa
				"VI",  //Americk� Panensk� ostrovy
				"AD",  //Andorra
				"AO",  //Angola
				"AI",  //Anguilla
				"AQ",  //Antarktida
				"AG",  //Antigua a Barbuda
				"AR",  //Argentina
				"AM",  //Arm�nie
				"AW",  //Aruba
				"AU",  //Austr�lie
				"AZ",  //�zerb�jd��n
				"BS",  //Bahamy
				"BH",  //Bahrajn
				"BD",  //Banglad�
				"BB",  //Barbados
				"BE",  //Belgie
				"BZ",  //Belize
				"BY",  //B�lorusko
				"BJ",  //Benin
				"BM",  //Bermudy
				"BT",  //Bh�t�n
				"BO",  //Bol�vie
				"BA",  //Bosna a Hercegovina
				"BW",  //Botswana
				"BV",  //Bouvet�v ostrov
				"BR",  //Braz�lie
				"IO",  //Britsk� Indickooce�nsk� �zem�
				"VG",  //Britsk� Panensk� ostrovy
				"BN",  //Brunej
				"BG",  //Bulharsko
				"BF",  //Burkina Faso
				"BI",  //Burundi
				"CK",  //Cookovy ostrovy
				"TD",  //�ad
				"CZ",  //�esko
				"CN",  //��na
				"DK",  //D�nsko
				"CD",  //Demokratick� republika Kongo
				"DM",  //Dominika
				"DO",  //Dominik�nsk� republika
				"DJ",  //D�ibutsko
				"EG",  //Egypt
				"EC",  //Ekv�dor
				"ER",  //Eritrea
				"EE",  //Estonsko
				"ET",  //Etiopie
				"FO",  //Faersk� ostrovy
				"FK",  //Falklandy (Malv�ny)
				"FJ",  //Fid�i
				"PH",  //Filip�ny
				"FI",  //Finsko
				"FR",  //Francie
				"GF",  //Francouzsk� Guyana
				"TF",  //Francouzsk� ji�n� �zem�
				"PF",  //Francouzsk� Polyn�sie
				"GA",  //Gabon
				"GM",  //Gambie
				"GH",  //Ghana
				"GI",  //Gibraltar
				"GD",  //Grenada
				"GL",  //Gr�nsko
				"GE",  //Gruzie
				"GP",  //Guadeloupe
				"GU",  //Guam
				"GT",  //Guatemala
				"GN",  //Guinea
				"GW",  //Guinea-Bissau
				"GY",  //Guyana
				"HT",  //Haiti
				"HM",  //Heard�v ostrov a McDonaldovy ostrovy
				"HN",  //Honduras
				"HK",  //Hongkong
				"CL",  //Chile
				"HR",  //Chorvatsko
				"IN",  //Indie
				"ID",  //Indon�sie
				"IQ",  //Ir�k
				"IR",  //�r�n
				"IE",  //Irsko
				"IS",  //Island
				"IT",  //It�lie
				"IL",  //Izrael
				"JM",  //Jamajka
				"JP",  //Japonsko
				"YE",  //Jemen
				"ZA",  //Jihoafrick� republika
				"GS",  //Ji�n� Georgie a Ji�n� Sandwichovy ostrovy
				"KR",  //Ji�n� Korea
				"JO",  //Jord�nsko
				"KY",  //Kajmansk� ostrovy
				"KH",  //Kambod�a
				"CM",  //Kamerun
				"CA",  //Kanada
				"CV",  //Kapverdy
				"QA",  //Katar
				"KZ",  //Kazachst�n
				"KE",  //Ke�a
				"KI",  //Kiribati
				"CC",  //Kokosov� ostrovy
				"CO",  //Kolumbie
				"KM",  //Komory
				"CG",  //Kongo
				"CR",  //Kostarika
				"CU",  //Kuba
				"KW",  //Kuvajt
				"CY",  //Kypr
				"KG",  //Kyrgyzst�n
				"LA",  //Laos
				"LS",  //Lesotho
				"LB",  //Libanon
				"LR",  //Lib�rie
				"LY",  //Libye
				"LI",  //Lichten�tejnsko
				"LT",  //Litva
				"LV",  //Loty�sko
				"LU",  //Lucembursko
				"MO",  //Macao
				"MG",  //Madagaskar
				"HU",  //Ma�arsko
				"MK",  //Makedonie
				"MY",  //Malajsie
				"MW",  //Malawi
				"MV",  //Maledivy
				"ML",  //Mali
				"MT",  //Malta
				"IM",  //Man - rezervov�no, prozat�m jako GB-IOM
				"MA",  //Maroko
				"MH",  //Marshallovy ostrovy
				"MQ",  //Martinik
				"MU",  //Mauricius
				"MR",  //Maurit�nie
				"YT",  //Mayotte
				"UM",  //Men�� odlehl� ostrovy USA
				"MX",  //Mexiko
				"FM",  //Mikron�sie
				"MD",  //Moldavsko
				"MC",  //Monako
				"MN",  //Mongolsko
				"MS",  //Montserrat
				"MZ",  //Mosambik
				"MM",  //Myanmar
				"NA",  //Namibie
				"NR",  //Nauru
				"DE",  //N�mecko
				"NP",  //Nep�l
				"NE",  //Niger
				"NG",  //Nig�rie
				"NI",  //Nikaragua
				"NU",  //Niue
				"AN",  //Nizozemsk� Antily
				"NL",  //Nizozemsko
				"NF",  //Norfolk
				"NO",  //Norsko
				"NC",  //Nov� Kaledonie
				"NZ",  //Nov� Z�land
				"OM",  //Om�n
				"PK",  //P�kist�n
				"PW",  //Palau
				"PS",  //Palestina
				"PA",  //Panama
				"PG",  //Papua-Nov� Guinea
				"PY",  //Paraguay
				"PE",  //Peru
				"PN",  //Pitcairn
				"CI",  //Pob�e�� slonoviny
				"PL",  //Polsko
				"PR",  //Portoriko
				"PT",  //Portugalsko
				"AT",  //Rakousko
				"RE",  //R�union
				"GQ",  //Rovn�kov� Guinea
				"RO",  //Rumunsko
				"RU",  //Rusko
				"RW",  //Rwanda
				"GR",  //�ecko
				"PM",  //Saint Pierre a Miquelon
				"SV",  //Salvador
				"WS",  //Samoa
				"SM",  //San Marino
				"SA",  //Sa�dsk� Ar�bie
				"SN",  //Senegal
				"KP",  //Severn� Korea
				"MP",  //Severn� Mariany
				"SC",  //Seychely
				"SL",  //Sierra Leone
				"SG",  //Singapur
				"SK",  //Slovensko
				"SI",  //Slovinsko
				"SO",  //Som�lsko
				"AE",  //Spojen� arabsk� emir�ty
				"GB",  //Spojen� kr�lovstv�
				"US",  //Spojen� st�ty americk�
				"CS",  //Srbsko a �ern� Hora
				"CF",  //St�edoafrick� republika
				"SD",  //S�d�n
				"SR",  //Surinam
				"SJ",  //Svalbard a ostrov Jan Mayen
				"SH",  //Svat� Helena
				"LC",  //Svat� Lucie
				"KN",  //Svat� Kry�tof a Nevis
				"ST",  //Svat� Tom� a Princ�v ostrov
				"VC",  //Svat� Vincenc a Grenadiny
				"SZ",  //Svazijsko
				"SY",  //S�rie
				"SB",  //�alamounovy ostrovy
				"ES",  //�pan�lsko
				"LK",  //�r� Lanka
				"SE",  //�v�dsko
				"CH",  //�v�carsko
				"TJ",  //T�d�ikist�n
				"TZ",  //Tanzanie
				"TH",  //Thajsko
				"TW",  //Tchaj-wan
				"TG",  //Togo
				"TK",  //Tokelau
				"TO",  //Tonga
				"TT",  //Trinidad a Tobago
				"TN",  //Tunisko
				"TR",  //Turecko
				"TM",  //Turkmenist�n
				"TC",  //Turks a Caicos
				"TV",  //Tuvalu
				"UG",  //Uganda
				"UA",  //Ukrajina
				"UY",  //Uruguay
				"UZ",  //Uzbekist�n
				"CX",  //V�no�n� ostrov
				"VU",  //Vanuatu
				"VA",  //Vatik�n
				"VE",  //Venezuela
				"VN",  //Vietnam
				"TL",  //V�chodn� Timor
				"WF",  //Wallis a Futuna
				"EH",  //Z�padn� Sahara
				"ZM",  //Zambie
				"ZW",  //Zimbabwe
			);
	}
}
?>
