var filename = window.location.href;
var url = filename.split('/');
var path;

if(url[2] == 'localhost')
{
  path = 'https://localhost/'+url[3]+'/assets';
}
else
{
  path = 'https://'+url[2]+'/'+url[3]+'/assets';
}

var input = document.querySelector("#phone_number");
window.intlTelInput(input, {
    customPlaceholder : function(selectedCountryPlaceholder, selectedCountryData){
       if(selectedCountryPlaceholder.substring(0,1) == '(')
       {
          return selectedCountryPlaceholder.replace(/\-+/g,'');
       }
       else
       {
          var placeholder = selectedCountryPlaceholder.replace(/^0| +|\-+/g,'');
          return placeholder;
       }
    },
    dropdownContainer: document.body,
    pageHiddenInput : url[4],
    initialCountry: "id",  
    onlyCountries: ['id','us', 'gb', 'sg', 'au', 'my'],
    placeholderNumberType: "MOBILE",
    utilsScript: path+"/intl-tel-input/js/utils.js",
});