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

var input = document.querySelector("#phone");
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
  // allowDropdown: false,
  // autoHideDialCode: false,
  // autoPlaceholder: "off",
  dropdownContainer: document.body,
  pageHiddenInput : url[4],
  // excludeCountries: ["us"],
  // formatOnDisplay: false,
  initialCountry: "id",
  /* geoIpLookup: function(callback) {
    $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
      var countryCode = (resp && resp.country) ? resp.country : "";
      callback(countryCode);
    });
  },*/
  // hiddenInput: "full_number",
  // localizedCountries: { 'de': 'Deutschland' },
  // nationalMode: false,
  onlyCountries: ['us', 'gb', 'sg', 'au', 'id','my'],
  placeholderNumberType: "MOBILE",
  // preferredCountries: ['cn', 'jp'],
  // separateDialCode: true,
  utilsScript: path+"/intl-tel-input/js/utils.js",
});