var swatchLength = $('.swatch-attribute').length;
if(swatchLength >= 1){
if($('.swatch-attribute').hasClass("size")){
$('.swatch-option').first().trigger('click');
}
}
$('.swatch-option.color').first().click();