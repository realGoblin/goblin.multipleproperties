<?
namespace MultuProp;
class CIBlockOption
{
  static $MODULE_ID="goblin.multipleproperties";
  public function GetUserTypeDescription()
  {
    return array(
      "PROPERTY_TYPE"        => "S", #-----один из стандартных типов
      "USER_TYPE"            => "MYIDCODE", #-----идентификатор типа свойства
      "DESCRIPTION"          => "Множественное свойство",
      "GetPropertyFieldHtml" => array("MultuProp\CIBlockOption", "GetPropertyFieldHtml"),
      "GetSettingsHTML" =>  array("MultuProp\CIBlockOption","GetSettingsHTML"),
      "ConvertToDB" => array("MultuProp\CIBlockOption","ConvertToDB"),
      "PrepareSettings"=>array("MultuProp\CIBlockOption","PrepareSettings"),
      "ConvertFromDB"=>array("MultuProp\CIBlockOption","ConvertFromDB")
    );
  }
  public function ConvertFromDB($arProperty, $value)
  {
    // ["FIELD"]
    // echo "--------------  arProperty -----------";
    // echo "<pre>";
    // var_dump($arProperty);
    // echo "</pre>";
    // echo "--------------  value-bd -----------";
    // echo "<pre>";
    // var_dump($value);
    // echo "</pre>";
    $result = array("VALUE"=>json_decode($value["VALUE"],true));
    // echo "<pre>";
    // var_dump($result);
    // echo "</pre>";
    // exit;
    return $result;
  }
  // перед сохранением настроек
  public function PrepareSettings($arFields)
  {
    // echo "<pre>";
    // var_dump($arFields);
    // echo "</pre>";
    // exit;
    $result = array(
      // "NUMBER_OF_FIELDS" => intval($arFields["USER_TYPE_SETTINGS"]["NUMBER_OF_FIELDS"]),
      "FIELD" =>  $arFields["USER_TYPE_SETTINGS"]["FIELD"],
  );
     return $result;
  }
  /*--------- вывод поля свойства на странице редактирования ---------*/
  public function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName)
  {
    // echo "--------------  arProperty -----------";
    // echo "<pre>";
    // var_dump($arProperty);
    // echo "</pre>";
    // echo "--------------  strHTMLControlName -----------";
    // echo "<pre>";
    // var_dump($strHTMLControlName);
    // echo "</pre>";
    // echo "--------------  value -----------";
    // echo "<pre>";
    // var_dump($value);
    // echo "</pre>";
    // $arResult = json_decode($value["VALUE"],true);
    $arResult = $value["VALUE"];
    // echo "--------------  arResult -----------";
    // echo "<pre>";
    // var_dump($arResult);
    // echo "</pre>";
    $returnString = "";
      foreach ($arProperty["USER_TYPE_SETTINGS"]["FIELD"] as $keyField => $valueField) {
        if((!is_array($arResult["FIELD"][$keyField]['VALUE'])&&$arResult["FIELD"][$keyField]['VALUE']!="")&&($valueField["TYPE"]=="file")){
          $returnString = $returnString.' '.$valueField["NAME_TYPE"]. ' <img src="'.\CFile::GetPath($arResult["FIELD"][$keyField]['VALUE']).'"><input type="hidden" name="'.$strHTMLControlName["VALUE"].'[FIELD]['.$keyField.'][VALUE]" value="'.$arResult["FIELD"][$keyField]['VALUE'].
          '"></br>';
        }elseif($valueField["TYPE"]=="text"&&$arProperty["ROW_COUNT"]>1){
          $returnString = $returnString.' '.$valueField["NAME_TYPE"]. ' <textarea  cols="'.$arProperty["COL_COUNT"].'" rows="'.$arProperty["ROW_COUNT"].'" name="'.$strHTMLControlName["VALUE"].'[FIELD]['.$keyField.'][VALUE]" value="'.$arResult["FIELD"][$keyField]['VALUE'].'"></textarea></br>';

        }else{
          $returnString = $returnString.' '.$valueField["NAME_TYPE"]. ' <input type="'.$valueField["TYPE"].'" name="'.$strHTMLControlName["VALUE"].'[FIELD]['.$keyField.'][VALUE]" value="'.$arResult["FIELD"][$keyField]['VALUE'].'"></br>';
        }
      }
    return $returnString;
  }
  // перед сохранением значения свойств
  public function ConvertToDB($arProperty, $value)
  {
    // echo "--------------  arProperty -----------";
    // echo "<pre>";
    // var_dump($arProperty);
    // echo "</pre>";
    // echo "--------------  +value+ -----------";
    // echo "<pre>";
    // var_dump($value);
    // echo "</pre>";
    foreach ($arProperty["USER_TYPE_SETTINGS"]["FIELD"] as $keyField => $valueField) {
      // echo "keyField";
      // var_dump($keyField);

      $thisValue  = $value["VALUE"]["FIELD"][$keyField]["VALUE"];
      // var_dump($value);
      // echo "--------------  +thisValue+ -----------";
        // var_dump($thisValue);
        // echo"<br>";
      if($valueField["TYPE"]=="file"){
        // echo "___________________________<br>";
        if(is_array($thisValue)){
          // echo "-------------+++-------------------<br>";
        $file = array(
          "name"=>$thisValue["name"],
          "size"=>$thisValue["size"],
          "tmp_name"=>$thisValue["tmp_name"],
          "type"=>$thisValue["type"],
          "old_file" => "",
          "del" => "Y",
          "MODULE_ID" => "iblock"
        );
        $idFileInBitrix = \CFile::SaveFile($file,"goblin.multipleproperties");
        $arResultItem["NAME"] = $valueField["NAME_TYPE"];
        $arResultItem["TYPE"] = $valueField["TYPE"];
        $arResultItem["VALUE"]  = $idFileInBitrix;
        $arResult["FIELD"][$keyField] = $arResultItem;
      }else{
        $arResultItem["NAME"] = $valueField["NAME_TYPE"];
        $arResultItem["TYPE"] = $valueField["TYPE"];
        $arResultItem["VALUE"]  = $thisValue;
        $arResult["FIELD"][$keyField] = $arResultItem;
      }
    }else{
        $arResultItem["NAME"] = $valueField["NAME_TYPE"];
        $arResultItem["TYPE"] = $valueField["TYPE"];
        $arResultItem["VALUE"]  = $thisValue;
        $arResult["FIELD"][$keyField] = $arResultItem;
      }
    }
    // echo "--------------  arResult -----------";
    // echo "<pre>";
    // var_dump($arResult);
    // echo "</pre>";
    $result=json_encode($arResult);
    $value["VALUE"] = $result;
    // echo "--------------  valueRes -----------";
    // echo "<pre>";
    // var_dump($value);
    // echo "</pre>";
    // $result=json_encode($value["VALUE"]);
    // echo "--------------  result -----------";
    // echo "<pre>";
    // var_dump($result);
    // echo "</pre>";
    // exit;
    return $result;
  }
  /*--------- вывод поля свойства на странице настроек ---------*/
  public function GetSettingsHTML($arProperty,$strHTMLControlName, $value)
  {
    $arJsConfig = [
            'multiprop' => [
                'js' => "/bitrix/js/multipleproperties.js",
                'rel' => []
            ]
        ];

        foreach ($arJsConfig as $ext => $arExt) {
            \CJSCore::RegisterExt($ext, $arExt);
        }
    \CUtil::InitJSCore('multiprop');
    // echo "--------------  strHTMLControlName -----------";
    // echo "<pre>";
    // var_dump($strHTMLControlName);
    // echo "</pre>";
    // echo "--------------  value -----------";
    // echo "<pre>";
    // var_dump($value);
    // echo "</pre>";
    // echo "--------------  arProperty -----------";
    // echo "<pre>";
    // var_dump($arProperty["USER_TYPE_SETTINGS"]);
    // echo "</pre>";
    $arPropertyFields = array(
           "HIDE" => array("FILTRABLE", "ROW_COUNT", "COL_COUNT", "DEFAULT_VALUE"),
           "SET" => array("FILTRABLE" => "N"),
           "USER_TYPE_SETTINGS_TITLE" => "Настройки даты/времени"
       );
       /*
       <select name="CAT" class="" id="">
       <option value="" <?if($type==''){echo "selected";}?>>Любая недвижимость</option>
       </select>
       */
       $button  = '<tr id="button"><td><input type="button" id="add" onclick="addField()" value="добавить"></td></tr>';
       $script  = '';/*
       $returnString = '<tr>
       <td>Число полей:</td>
       <td><input type="text" value="'.$arProperty["USER_TYPE_SETTINGS"]["NUMBER_OF_FIELDS"].'" size="5" name="'.$strHTMLControlName["NAME"].'[NUMBER_OF_FIELDS]"></td>
       </tr><br>';*/
       $returnString  = "";
         foreach ($arProperty["USER_TYPE_SETTINGS"]["FIELD"] as $key => $value){
           $numberField = $key+1;
           $returnString = $returnString.'
           <tr  id="field_'.$key.'">
            <td>Поле '.$numberField.':</td>
            <td>
              <input type="text" class="field" name="'.$strHTMLControlName["NAME"].'[FIELD]['.$key.'][NAME_TYPE]" value="'.$value["NAME_TYPE"].'">
            </td>
            <td>
              <select name="'.$strHTMLControlName["NAME"].'[FIELD]['.$key.'][TYPE]" class="" id="">
                <option value="text" '.(($value["TYPE"]=="text") ? 'selected' : '').' >текст</option>
                <option value="file" '. (($value["TYPE"]=="file") ? 'selected' : '').' >file</option>
              </select>
            </td>
            <td><input type="button" onclick="dellField('.$key.')" value="Удалить"></td>
           </tr><br>';
         }
         $returnString  = $returnString.$button.$script;
       return $returnString;
  }
}
