<?php
/*
 * $Id: iso3166.php,v 1.1 2006/09/05 13:18:10 kozlik Exp $
 */

class ISO3166{
	function get_country_codes(){
		return array(
				"AF",  //Afghánistán
				"AL",  //Albánie
				"DZ",  //Al¾írsko
				"AS",  //Americká Samoa
				"VI",  //Americké Panenské ostrovy
				"AD",  //Andorra
				"AO",  //Angola
				"AI",  //Anguilla
				"AQ",  //Antarktida
				"AG",  //Antigua a Barbuda
				"AR",  //Argentina
				"AM",  //Arménie
				"AW",  //Aruba
				"AU",  //Austrálie
				"AZ",  //Ázerbájd¾án
				"BS",  //Bahamy
				"BH",  //Bahrajn
				"BD",  //Bangladé¹
				"BB",  //Barbados
				"BE",  //Belgie
				"BZ",  //Belize
				"BY",  //Bìlorusko
				"BJ",  //Benin
				"BM",  //Bermudy
				"BT",  //Bhútán
				"BO",  //Bolívie
				"BA",  //Bosna a Hercegovina
				"BW",  //Botswana
				"BV",  //Bouvetùv ostrov
				"BR",  //Brazílie
				"IO",  //Britské Indickooceánské území
				"VG",  //Britské Panenské ostrovy
				"BN",  //Brunej
				"BG",  //Bulharsko
				"BF",  //Burkina Faso
				"BI",  //Burundi
				"CK",  //Cookovy ostrovy
				"TD",  //Èad
				"CZ",  //Èesko
				"CN",  //Èína
				"DK",  //Dánsko
				"CD",  //Demokratická republika Kongo
				"DM",  //Dominika
				"DO",  //Dominikánská republika
				"DJ",  //D¾ibutsko
				"EG",  //Egypt
				"EC",  //Ekvádor
				"ER",  //Eritrea
				"EE",  //Estonsko
				"ET",  //Etiopie
				"FO",  //Faerské ostrovy
				"FK",  //Falklandy (Malvíny)
				"FJ",  //Fid¾i
				"PH",  //Filipíny
				"FI",  //Finsko
				"FR",  //Francie
				"GF",  //Francouzská Guyana
				"TF",  //Francouzská ji¾ní území
				"PF",  //Francouzská Polynésie
				"GA",  //Gabon
				"GM",  //Gambie
				"GH",  //Ghana
				"GI",  //Gibraltar
				"GD",  //Grenada
				"GL",  //Grónsko
				"GE",  //Gruzie
				"GP",  //Guadeloupe
				"GU",  //Guam
				"GT",  //Guatemala
				"GN",  //Guinea
				"GW",  //Guinea-Bissau
				"GY",  //Guyana
				"HT",  //Haiti
				"HM",  //Heardùv ostrov a McDonaldovy ostrovy
				"HN",  //Honduras
				"HK",  //Hongkong
				"CL",  //Chile
				"HR",  //Chorvatsko
				"IN",  //Indie
				"ID",  //Indonésie
				"IQ",  //Irák
				"IR",  //Írán
				"IE",  //Irsko
				"IS",  //Island
				"IT",  //Itálie
				"IL",  //Izrael
				"JM",  //Jamajka
				"JP",  //Japonsko
				"YE",  //Jemen
				"ZA",  //Jihoafrická republika
				"GS",  //Ji¾ní Georgie a Ji¾ní Sandwichovy ostrovy
				"KR",  //Ji¾ní Korea
				"JO",  //Jordánsko
				"KY",  //Kajmanské ostrovy
				"KH",  //Kambod¾a
				"CM",  //Kamerun
				"CA",  //Kanada
				"CV",  //Kapverdy
				"QA",  //Katar
				"KZ",  //Kazachstán
				"KE",  //Keòa
				"KI",  //Kiribati
				"CC",  //Kokosové ostrovy
				"CO",  //Kolumbie
				"KM",  //Komory
				"CG",  //Kongo
				"CR",  //Kostarika
				"CU",  //Kuba
				"KW",  //Kuvajt
				"CY",  //Kypr
				"KG",  //Kyrgyzstán
				"LA",  //Laos
				"LS",  //Lesotho
				"LB",  //Libanon
				"LR",  //Libérie
				"LY",  //Libye
				"LI",  //Lichten¹tejnsko
				"LT",  //Litva
				"LV",  //Loty¹sko
				"LU",  //Lucembursko
				"MO",  //Macao
				"MG",  //Madagaskar
				"HU",  //Maïarsko
				"MK",  //Makedonie
				"MY",  //Malajsie
				"MW",  //Malawi
				"MV",  //Maledivy
				"ML",  //Mali
				"MT",  //Malta
				"IM",  //Man - rezervováno, prozatím jako GB-IOM
				"MA",  //Maroko
				"MH",  //Marshallovy ostrovy
				"MQ",  //Martinik
				"MU",  //Mauricius
				"MR",  //Mauritánie
				"YT",  //Mayotte
				"UM",  //Men¹í odlehlé ostrovy USA
				"MX",  //Mexiko
				"FM",  //Mikronésie
				"MD",  //Moldavsko
				"MC",  //Monako
				"MN",  //Mongolsko
				"MS",  //Montserrat
				"MZ",  //Mosambik
				"MM",  //Myanmar
				"NA",  //Namibie
				"NR",  //Nauru
				"DE",  //Nìmecko
				"NP",  //Nepál
				"NE",  //Niger
				"NG",  //Nigérie
				"NI",  //Nikaragua
				"NU",  //Niue
				"AN",  //Nizozemské Antily
				"NL",  //Nizozemsko
				"NF",  //Norfolk
				"NO",  //Norsko
				"NC",  //Nová Kaledonie
				"NZ",  //Nový Zéland
				"OM",  //Omán
				"PK",  //Pákistán
				"PW",  //Palau
				"PS",  //Palestina
				"PA",  //Panama
				"PG",  //Papua-Nová Guinea
				"PY",  //Paraguay
				"PE",  //Peru
				"PN",  //Pitcairn
				"CI",  //Pobøe¾í slonoviny
				"PL",  //Polsko
				"PR",  //Portoriko
				"PT",  //Portugalsko
				"AT",  //Rakousko
				"RE",  //Réunion
				"GQ",  //Rovníková Guinea
				"RO",  //Rumunsko
				"RU",  //Rusko
				"RW",  //Rwanda
				"GR",  //Øecko
				"PM",  //Saint Pierre a Miquelon
				"SV",  //Salvador
				"WS",  //Samoa
				"SM",  //San Marino
				"SA",  //Saúdská Arábie
				"SN",  //Senegal
				"KP",  //Severní Korea
				"MP",  //Severní Mariany
				"SC",  //Seychely
				"SL",  //Sierra Leone
				"SG",  //Singapur
				"SK",  //Slovensko
				"SI",  //Slovinsko
				"SO",  //Somálsko
				"AE",  //Spojené arabské emiráty
				"GB",  //Spojené království
				"US",  //Spojené státy americké
				"CS",  //Srbsko a Èerná Hora
				"CF",  //Støedoafrická republika
				"SD",  //Súdán
				"SR",  //Surinam
				"SJ",  //Svalbard a ostrov Jan Mayen
				"SH",  //Svatá Helena
				"LC",  //Svatá Lucie
				"KN",  //Svatý Kry¹tof a Nevis
				"ST",  //Svatý Tomá¹ a Princùv ostrov
				"VC",  //Svatý Vincenc a Grenadiny
				"SZ",  //Svazijsko
				"SY",  //Sýrie
				"SB",  //©alamounovy ostrovy
				"ES",  //©panìlsko
				"LK",  //©rí Lanka
				"SE",  //©védsko
				"CH",  //©výcarsko
				"TJ",  //Tád¾ikistán
				"TZ",  //Tanzanie
				"TH",  //Thajsko
				"TW",  //Tchaj-wan
				"TG",  //Togo
				"TK",  //Tokelau
				"TO",  //Tonga
				"TT",  //Trinidad a Tobago
				"TN",  //Tunisko
				"TR",  //Turecko
				"TM",  //Turkmenistán
				"TC",  //Turks a Caicos
				"TV",  //Tuvalu
				"UG",  //Uganda
				"UA",  //Ukrajina
				"UY",  //Uruguay
				"UZ",  //Uzbekistán
				"CX",  //Vánoèní ostrov
				"VU",  //Vanuatu
				"VA",  //Vatikán
				"VE",  //Venezuela
				"VN",  //Vietnam
				"TL",  //Východní Timor
				"WF",  //Wallis a Futuna
				"EH",  //Západní Sahara
				"ZM",  //Zambie
				"ZW",  //Zimbabwe
			);
	}
}
?>
