function addField(){
  butt  = document.getElementById("button");
  key = document.getElementsByClassName('field').length;
  numperField = key+1;
  newInput  = '<tr  id="field_'+key+'"><td>Поле '+numperField+':</td>'+
  '<td><input type="text" class="field" name="PROPERTY_USER_TYPE_SETTINGS[FIELD]['+key+'][NAME_TYPE]" value=""></td><td>'+
  '<select name="PROPERTY_USER_TYPE_SETTINGS[FIELD]['+key+'][TYPE]" class="" id="">'+
  '<option value="text">текст</option><option value="file">file</option></select></td>'+
  '<td><input type="button" onclick="dellField('+key+')" value="Удалить"></td></tr><br>';
  butt.insertAdjacentHTML("beforebegin",newInput);
}
function dellField(key){
document.getElementById("field_"+key).remove();
}
