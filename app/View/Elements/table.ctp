<?php
define("ONE_CELL_WIDTH", 15);

/**
 * The element requires two array $options and $data
 * The options array examples can be found in View/SrfViewer/SF$options/Option
 */
$html = "";
// generate header
$th = "";
$isInlineCss = true;
$inlineCss = array();
if (isset($options['isInlineCss'])) {
	$isInlineCss = $options['isInlineCss'];
}
$isFirstRowAsHeader = false;
if (isset($options['firstRowAsHeader'])) {
	$isFirstRowAsHeader = $options['firstRowAsHeader'];
}

if ($isInlineCss) {
	$html .= <<<HTML
<style>
  .text-right{
  	text-align: right;
  }
  .col-sm-1{
  	width: 8.3%;
  }
  .col-sm-2{
  	width: 16.7%;
  }
  .col-sm-3{
  	width: 25%;
  }
  .col-sm-4{
  	width: 33.3%;
  }
  .col-sm-5{
  	width:41.7%;
  }
  .col-sm-6{
  	width:50%;
  }
  .col-sm-7{
  	width:58.3%;
  }
  .col-sm-8{
  	width:66.7%;
  }
  .col-sm-9{
  	width:75%;
  }
  .col-sm-10{
  	width:83.3%;
  }
  .col-sm-11{
  	width:91.7%;
  }
  .col-sm-12{
  	width:100%;
  }
  .textheader{
  	font-weight: bold;
  	font-size: 12pt;
  }
  .textbody{
  	font-weight: normal;
  	font-size: 10pt;
  }
  table{
  	padding: 5px;
  }
  .danger{
  	background-color: lightgoldenrodyellow;
  }
</style>
<body>
HTML;

}

$html .= '<table class="table">';
if (isset($options['noHeader']) && $options['noHeader']) {
} else {

	for ($j = 0; $j < count($options['colNames']); $j++) {
		$tdClasses = "";
		$tdStyle = "";

		if ($j == 0) {
			$tdClasses .= "first";
		}
		$tdClasses .= $isInlineCss ? " textheader" : "";
		if (isset($options['colModel'][$j]["align"])) {
			$tdClasses .= " text-" . $options['colModel'][$j]["align"];
		}
		if (isset($options['colModel'][$j]["width"])) {
			$tdClasses .= " col-sm-" . $options['colModel'][$j]["width"];
		}

		$cellVal = htmlentities(
			($isFirstRowAsHeader && count(
				$data
			) > 0) ? $data[0][$options['colModel'][$j]['name']] : $options['colModel'][$j]['name']
		);
		$tdClasses = 'class="' . $tdClasses . '"';
		$tdStyle = "style=\"$tdStyle\"";
		$th .= "<td $tdClasses $tdStyle>" . $cellVal . "</td>";
	}
	$html .= "<thead><tr>$th</tr></thead>";
	if (isset($options['showFooter']) && $options['showFooter']) {
		$html .= "<tfoot><tr>$th</tr></tfoot>";
	}

}

// generate body
$html .= "<tbody>";
for ($i = ($isFirstRowAsHeader ? 1 : 0); $i < count($data); $i++) {
	$attr = "";
	if (isset($options['conditionMapPhp'])) {
		$mapFunction = $options['conditionMapPhp'];
		$mapFunction = str_ireplace("#datavar#", '$data[$i]', $mapFunction);
		$mapFunction = str_ireplace("#outputvar#", '$attr', $mapFunction);
		eval($mapFunction);
	}
	$html .= "<tr $attr>";

	for ($j = 0; $j < count($options['colNames']); $j++) {
		$tdClasses = "";
		$tdStyle = "";
		if ($j == 0) {
			$tdClasses .= "first";
		}
		$tdClasses .= $isInlineCss ? " textbody" : "";

		if (isset($options['colModel'][$j]["align"])) {
			$tdClasses .= " text-" . $options['colModel'][$j]["align"];
		}
		if (isset($options['colModel'][$j]["width"])) {
			$tdClasses .= " col-sm-" . $options['colModel'][$j]["width"];
		}

		$tdClasses = "class=\"$tdClasses\"";
		$tdStyle = "style=\"$tdStyle\"";
		$html .= "<td $tdClasses $tdStyle>" . htmlentities($data[$i][$options['colModel'][$j]['name']]) . "</td>";
	}

	$html .= "</tr>";
}
$html .= "</tbody>";
$html .= "</table>";


if ($isInlineCss) {
	$html .= "</body>";
}

echo $html;

?>