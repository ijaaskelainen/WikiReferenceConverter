<html>
<body>
<form action="WikiReferenceConverter.php" method="post">
<div><input type="text" name="content"></div>
<div><input type="submit"></div>
</form>

<?php

class WikiReferenceConverter
{
	public $name	= "Wikipedia Reference Converter";
	public $desc	= "Convert Wikipedia references from English Wikipedia into Finnish Wikipedia ..";
	public $ver	= "0.0.1 Alpha";

	// $to, $from, $from, ...
	private $conversion_array = array(
		// wikidate associated
		array("Tammikuuta", "01", "January", "Tammikuu"),
		array("Helmikuuta", "02", "February", "Helmikuu"),
		array("Maaliskuuta", "03", "March", "Maaliskuu"),
		array("Huhtikuuta", "04", "April", "Huhtikuu"),
		array("Toukokuuta", "05", "May", "Toukokuu"),
		array("Kesäkuuta", "06", "June", "Kesäkuu"),
		array("Heinäkuuta", "07", "July", "Heinäkuu"),
		array("Elokuuta", "08", "August", "Elokuu"),
		array("Syyskuuta", "09", "September", "Syyskuu"),
		array("Lokakuuta", "10", "October", "Lokakuu"),
		array("Marraskuuta", "11", "November", "Marraskuu"),
		array("Joulukuuta", "12", "December", "Joulukuu"),
		// citations associated
		array("osoite", "url"),
		array("tekijä", "author"),
		array("nimike", "title"),
		array("ajankohta", "date"),
		array("julkaisu", "work"),
//		array("julkaisija", "work"),
		array("arkisto", "archive-url", "arkisto"),
		array("arkistoitu", "archive-date", "arkistoitu")
	);

	public function wikidate($str)
	{
		$exploded_str = explode(" ", $str);

		// fault handler
		if (count($exploded_str) < 3)
			return $str;

		if ($exploded_str[0] < 10)
			$exploded_str[0] = "0".$exploded_str[0];

		for ($n = 0; $n < count($this->conversion_array); $n++)
		{
			foreach ($this->conversion_array[$n] as $_conversion_array)
			{
				if (strcasecmp($exploded_str[1], $_conversion_array) == 0)
					$exploded_str[1] = $this->conversion_array[$n][0];
			}
		}
		return implode(" ", $exploded_str);
	}

	public function convert($str)
	{
		for ($n = 0; $n < count($this->conversion_array); $n++)
		{
			foreach ($this->conversion_array[$n] as $_conversion_array)
			{
				if (strcasecmp($str, $_conversion_array) == 0)
				{
					return $this->conversion_array[$n][0];
				}
			}
		}
	}
}

$WRC = new WikiReferenceConverter();

// $string = explode("|", "<ref>{{cite web|url=https://www.nationalgeographic.com/people-and-culture/food/the-plate/2016/07/olives--the-bitter-truth/|author=Rupp R.|title=The bitter truth about olives|date=1 July 2016|work=National Geographic|access-date=24 June 2019|archive-date=10 July 2019|archive-url=https://web.archive.org/web/20190710080202/https://www.nationalgeographic.com/people-and-culture/food/the-plate/2016/07/olives--the-bitter-truth/|url-status=live}}</ref>");
$string = explode("|", $_POST["content"]);
$string = str_replace("{{", "", $string);
$string = str_replace("}}", "", $string);
foreach ($string as $value)
{
	$string2 = explode("=", $value);

						// generate $key => $value pairs
	if(!isset($string2[1]))			// fault
		continue;			// handler
	$key = $WRC->convert($string2[0]);	// key
	$$key = $WRC->wikidate($string2[1]);	// value
}
$viitattu = $WRC->wikidate(date("j m Y"));
$kieli = "{{en}}";

echo "&lt;ref&gt;{{Verkkoviite | Osoite = ".$osoite." | Nimeke = ".$nimike." | Tekijä = ".$tekijä." | Julkaisu = ".$julkaisu." | Julkaisupaikka =  | Ajankohta = ".$ajankohta." | Julkaisija = ".$julkaisija." | Arkisto = ".$arkisto." | Arkistoitu = ".$arkistoitu." | Tiedostomuoto = | Selite = | Lainaus = | Viitattu = ".$viitattu." | Kieli = ".$kieli." }}&lt;/ref&gt;";

?>

</body>
</html> 
