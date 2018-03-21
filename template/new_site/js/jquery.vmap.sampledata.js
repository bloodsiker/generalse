var lang = $('meta[name="lang"]').attr('content');

var sample_data = {
    "ua": "100",
    "by": "100",
    "md": "100",
    "ge": "100",
    "am": "100",
    // "az": "100",
    // "ee": "100"
};
var tooltips = {};
if(lang == 'ru'){
    tooltips = {
        ua: 'Украина',
        by: 'Беларусь',
        md: 'Молдова',
        ge: 'Грузия',
        am: 'Армения',
    };
} else if(lang == 'en') {
    tooltips = {
        ua: 'Ukraine',
        by: 'Belarus',
        md: 'Moldova',
        ge: 'Georgia',
        am: 'Armenia',
    };
}
