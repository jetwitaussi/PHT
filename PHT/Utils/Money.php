<?php
/**
 * PHT
 *
 * @author Telesphore
 * @link https://github.com/jetwitaussi/PHT
 * @version 3.0
 * @license "THE BEER-WARE LICENSE" (Revision 42):
 *          Telesphore wrote this file.  As long as you retain this notice you
 *          can do whatever you want with this stuff. If we meet some day, and you think
 *          this stuff is worth it, you can buy me a beer in return.
 */

namespace PHT\Utils;

class Money
{
    const ALIRAQ = 5;
    const LEAGUEID128 = 5;
    const COUNTRYID135 = 5;
    const ALJAZAIR = 0.1;
    const LEAGUEID118 = 0.1;
    const COUNTRYID126 = 0.1;
    const ALKUWAYT = 25;
    const LEAGUEID127 = 25;
    const COUNTRYID134 = 25;
    const ALMAGHRIB = 1;
    const LEAGUEID77 = 1;
    const COUNTRYID72 = 1;
    const ALURDUN = 5;
    const LEAGUEID106 = 5;
    const COUNTRYID103 = 5;
    const ALYAMAN = 0.1;
    const LEAGUEID133 = 0.1;
    const COUNTRYID139 = 0.1;
    const ANDORRA = 10;
    const LEAGUEID105 = 10;
    const COUNTRYID101 = 10;
    const ANGOLA = 0.1;
    const LEAGUEID130 = 0.1;
    const COUNTRYID137 = 0.1;
    const ARGENTINA = 10;
    const LEAGUEID7 = 10;
    const COUNTRYID7 = 10;
    const AZERBAYCAN = 10;
    const LEAGUEID129 = 10;
    const COUNTRYID133 = 10;
    const BAHRAIN = 20;
    const LEAGUEID123 = 20;
    const COUNTRYID129 = 20;
    const BANGLADESH = 0.2;
    const LEAGUEID132 = 0.2;
    const COUNTRYID138 = 0.2;
    const BARBADOS = 5;
    const LEAGUEID124 = 5;
    const COUNTRYID130 = 5;
    const BELARUS = 5;
    const LEAGUEID91 = 5;
    const COUNTRYID87 = 5;
    const BELGIE = 10;
    const LEAGUEID44 = 10;
    const COUNTRYID38 = 10;
    const BENIN = 10;
    const LEAGUEID139 = 10;
    const COUNTRYID147 = 10;
    const BOLIVIA = 1;
    const LEAGUEID74 = 1;
    const COUNTRYID69 = 1;
    const BOSNIAANDHERCEGOVINA = 5;
    const LEAGUEID69 = 5;
    const COUNTRYID63 = 5;
    const BRASIL = 5;
    const LEAGUEID16 = 5;
    const COUNTRYID22 = 5;
    const BULGARIA = 5;
    const LEAGUEID62 = 5;
    const COUNTRYID55 = 5;
    const CABOVERDE = 0.1;
    const LEAGUEID125 = 0.1;
    const COUNTRYID131 = 0.1;
    const CANADA = 5;
    const LEAGUEID17 = 5;
    const COUNTRYID14 = 5;
    const CESKAREPUBLIKA = 0.25;
    const LEAGUEID52 = 0.25;
    const COUNTRYID46 = 0.25;
    const CHILE = 50;
    const LEAGUEID18 = 50;
    const COUNTRYID17 = 50;
    const CHINA = 1;
    const LEAGUEID34 = 1;
    const COUNTRYID28 = 1;
    const CHINESETAIPEI = 10;
    const LEAGUEID60 = 10;
    const COUNTRYID52 = 10;
    const COLOMBIA = 10;
    const LEAGUEID19 = 10;
    const COUNTRYID18 = 10;
    const COSTARICA = 4;
    const LEAGUEID81 = 4;
    const COUNTRYID77 = 4;
    const IVORYCOST = 20;
    const LEAGUEID126 = 20;
    const COUNTRYID132 = 20;
    const CRNAGORA = 10;
    const LEAGUEID131 = 10;
    const COUNTRYID136 = 10;
    const CYMRU = 15;
    const LEAGUEID61 = 15;
    const COUNTRYID56 = 15;
    const CYPRUS = 5;
    const LEAGUEID89 = 5;
    const COUNTRYID82 = 5;
    const DANMARK = 1;
    const LEAGUEID11 = 1;
    const COUNTRYID10 = 1;
    const DEUTSCHLAND = 10;
    const LEAGUEID3 = 10;
    const COUNTRYID3 = 10;
    const ECUADOR = 10;
    const LEAGUEID73 = 10;
    const COUNTRYID68 = 10;
    const EESTI = 0.5;
    const LEAGUEID56 = 0.5;
    const COUNTRYID47 = 0.5;
    const ELSALVADOR = 10;
    const LEAGUEID100 = 10;
    const COUNTRYID96 = 10;
    const ENGLAND = 15;
    const LEAGUEID2 = 15;
    const COUNTRYID2 = 15;
    const ESPANA = 10;
    const LEAGUEID36 = 10;
    const COUNTRYID35 = 10;
    const FOROYAR = 1;
    const LEAGUEID76 = 1;
    const COUNTRYID71 = 1;
    const FRANCE = 10;
    const LEAGUEID5 = 10;
    const COUNTRYID5 = 10;
    const GUATEMALA = 10;
    const LEAGUEID107 = 10;
    const COUNTRYID102 = 10;
    const HANGUK = 10;
    const LEAGUEID30 = 10;
    const COUNTRYID29 = 10;
    const HAYASTAN = 20;
    const LEAGUEID122 = 20;
    const COUNTRYID104 = 20;
    const HELLAS = 10;
    const LEAGUEID50 = 10;
    const COUNTRYID45 = 10;
    const HONDURAS = 5;
    const LEAGUEID99 = 5;
    const COUNTRYID95 = 5;
    const HONGKONG = 1;
    const LEAGUEID59 = 1;
    const COUNTRYID53 = 1;
    const HRVATSKA = 1;
    const LEAGUEID58 = 1;
    const COUNTRYID42 = 1;
    const INDIA = 0.25;
    const LEAGUEID20 = 0.25;
    const COUNTRYID27 = 0.25;
    const INDONESIA = 1;
    const LEAGUEID54 = 1;
    const COUNTRYID49 = 1;
    const IRAN = 1;
    const LEAGUEID85 = 1;
    const COUNTRYID80 = 1;
    const IRELAND = 10;
    const LEAGUEID21 = 10;
    const COUNTRYID16 = 10;
    const ISLAND = 0.1;
    const LEAGUEID38 = 0.1;
    const COUNTRYID37 = 0.1;
    const ISRAEL = 2;
    const LEAGUEID63 = 2;
    const COUNTRYID51 = 2;
    const ITALIA = 10;
    const LEAGUEID4 = 10;
    const COUNTRYID4 = 10;
    const JAMAICA = 0.5;
    const LEAGUEID94 = 0.5;
    const COUNTRYID89 = 0.5;
    const KAZAKHSTAN = 0.1;
    const LEAGUEID112 = 0.1;
    const COUNTRYID122 = 0.1;
    const KENYA = 0.5;
    const LEAGUEID95 = 0.5;
    const COUNTRYID90 = 0.5;
    const KYRGYZSTAN = 0.2;
    const LEAGUEID102 = 0.2;
    const COUNTRYID98 = 0.2;
    const LATVIJA = 20;
    const LEAGUEID53 = 20;
    const COUNTRYID48 = 20;
    const LETZEBUERG = 10;
    const LEAGUEID84 = 10;
    const COUNTRYID79 = 10;
    const LIECHTENSTEIN = 5;
    const LEAGUEID117 = 5;
    const COUNTRYID125 = 5;
    const LIETUVA = 2.5;
    const LEAGUEID66 = 2.5;
    const COUNTRYID61 = 2.5;
    const LUBNAN = 5;
    const LEAGUEID120 = 5;
    const COUNTRYID128 = 5;
    const MAGYARORSZAG = 50;
    const LEAGUEID51 = 50;
    const COUNTRYID44 = 50;
    const MAKEDONIJA = 0.5;
    const LEAGUEID97 = 0.5;
    const COUNTRYID92 = 0.5;
    const MALAYSIA = 2.5;
    const LEAGUEID45 = 2.5;
    const COUNTRYID39 = 2.5;
    const MALTA = 10;
    const LEAGUEID101 = 10;
    const COUNTRYID97 = 10;
    const MEXICO = 1;
    const LEAGUEID6 = 1;
    const COUNTRYID6 = 1;
    const MISR = 2.5;
    const LEAGUEID33 = 2.5;
    const COUNTRYID32 = 2.5;
    const MOCAMBIQUE = 0.4;
    const LEAGUEID135 = 0.4;
    const COUNTRYID142 = 0.4;
    const MOLDOVA = 0.5;
    const LEAGUEID103 = 0.5;
    const COUNTRYID99 = 0.5;
    const MONGOLULS = 5;
    const LEAGUEID119 = 5;
    const COUNTRYID127 = 5;
    const NEDERLAND = 10;
    const LEAGUEID14 = 10;
    const COUNTRYID12 = 10;
    const NEGARABRUNEIDARUSSALAM = 5;
    const LEAGUEID136 = 5;
    const COUNTRYID143 = 5;
    const NICARAGUA = 0.5;
    const LEAGUEID111 = 0.5;
    const COUNTRYID121 = 0.5;
    const NIGERIA = 0.1;
    const LEAGUEID75 = 0.1;
    const COUNTRYID70 = 0.1;
    const NIPPON = 0.1;
    const LEAGUEID22 = 0.1;
    const COUNTRYID25 = 0.1;
    const NORGE = 1;
    const LEAGUEID9 = 1;
    const COUNTRYID9 = 1;
    const NORTHERNIRELAND = 15;
    const LEAGUEID93 = 15;
    const COUNTRYID88 = 15;
    const OCEANIA = 5;
    const LEAGUEID15 = 5;
    const COUNTRYID13 = 5;
    const OSTERREICH = 10;
    const LEAGUEID39 = 10;
    const COUNTRYID33 = 10;
    const PAKISTAN = 0.2;
    const LEAGUEID71 = 0.2;
    const COUNTRYID64 = 0.2;
    const PANAMA = 10;
    const LEAGUEID96 = 10;
    const COUNTRYID91 = 10;
    const PARAGUAY = 2;
    const LEAGUEID72 = 2;
    const COUNTRYID67 = 2;
    const PERU = 10;
    const LEAGUEID23 = 10;
    const COUNTRYID21 = 10;
    const PHILIPPINES = 0.25;
    const LEAGUEID55 = 0.25;
    const COUNTRYID50 = 0.25;
    const POLSKA = 2.5;
    const LEAGUEID24 = 2.5;
    const COUNTRYID26 = 2.5;
    const PORTUGAL = 10;
    const LEAGUEID25 = 10;
    const COUNTRYID23 = 10;
    const PRATEHKAMPUCHEA = 2.5;
    const LEAGUEID138 = 2.5;
    const COUNTRYID145 = 2.5;
    const PRATHETTHAI = 0.25;
    const LEAGUEID31 = 0.25;
    const COUNTRYID30 = 0.25;
    const REPUBLICOFGHANA = 10;
    const LEAGUEID137 = 10;
    const COUNTRYID144 = 10;
    const REPUBLICADOMINICANA = 0.5;
    const LEAGUEID88 = 0.5;
    const COUNTRYID83 = 0.5;
    const ROMANIA = 0.5;
    const LEAGUEID37 = 0.5;
    const COUNTRYID36 = 0.5;
    const ROSSIYA = 0.25;
    const LEAGUEID35 = 0.25;
    const COUNTRYID34 = 0.25;
    const SAKARTVELO = 5;
    const LEAGUEID104 = 5;
    const COUNTRYID100 = 5;
    const SAUDIARABIA = 2.5;
    const LEAGUEID79 = 2.5;
    const COUNTRYID75 = 2.5;
    const SCHWEIZ = 5;
    const LEAGUEID46 = 5;
    const COUNTRYID40 = 5;
    const SCOTLAND = 15;
    const LEAGUEID26 = 15;
    const COUNTRYID15 = 15;
    const SENEGAL = 20;
    const LEAGUEID121 = 20;
    const COUNTRYID86 = 20;
    const SHQIPERIA = 50;
    const LEAGUEID98 = 50;
    const COUNTRYID94 = 50;
    const SINGAPORE = 5;
    const LEAGUEID47 = 5;
    const COUNTRYID41 = 5;
    const SLOVENIJA = 10;
    const LEAGUEID64 = 10;
    const COUNTRYID57 = 10;
    const SLOVENSKO = 0.2;
    const LEAGUEID67 = 0.2;
    const COUNTRYID66 = 0.2;
    const SOUTHAFRICA = 1.25;
    const LEAGUEID27 = 1.25;
    const COUNTRYID24 = 1.25;
    const SRBIJA = 1;
    const LEAGUEID57 = 1;
    const COUNTRYID43 = 1;
    const SUOMI = 10;
    const LEAGUEID12 = 10;
    const COUNTRYID11 = 10;
    const SURINAME = 5;
    const LEAGUEID113 = 5;
    const COUNTRYID123 = 5;
    const SURIYAH = 10;
    const LEAGUEID140 = 10;
    const COUNTRYID148 = 10;
    const SVERIGE = 1;
    const LEAGUEID1 = 1;
    const COUNTRYID1 = 1;
    const TOUNES = 7;
    const LEAGUEID80 = 7;
    const COUNTRYID76 = 7;
    const TRINIDADANDTOBAGO = 1;
    const LEAGUEID110 = 1;
    const COUNTRYID105 = 1;
    const TURKIYE = 10;
    const LEAGUEID32 = 10;
    const COUNTRYID31 = 10;
    const UKRAINA = 2;
    const LEAGUEID68 = 2;
    const COUNTRYID62 = 2;
    const UMAN = 20;
    const LEAGUEID134 = 20;
    const COUNTRYID140 = 20;
    const UNITEDARABEMIRATES = 4;
    const LEAGUEID83 = 4;
    const COUNTRYID78 = 4;
    const URUGUAY = 1;
    const LEAGUEID28 = 1;
    const COUNTRYID19 = 1;
    const USA = 10;
    const LEAGUEID8 = 10;
    const COUNTRYID8 = 10;
    const VENEZUELA = 10;
    const LEAGUEID29 = 10;
    const COUNTRYID20 = 10;
    const VIETNAM = 1;
    const LEAGUEID70 = 1;
    const COUNTRYID65 = 1;

    /**
     * Convert a money amount into country currency
     *
     * @param integer $amount
     * @param integer $country
     * @return integer
     */
    public static function convert($amount, $country)
    {
        if ($country !== null) {
            return round($amount / $country);
        }
        return $amount;
    }

    /**
     * Convert a money amount from a country currency to SEK amount
     *
     * @param integer $amount
     * @param integer $country
     * @return integer
     */
    public static function toSEK($amount, $country)
    {
        return floor($amount * $country);
    }

}
