import Tagify from '@yaireo/tagify';
import '@yaireo/tagify/dist/tagify.css';

window.tagify = Tagify;

var list = document.getElementById('whitelist');
var programs;
if (list) {
    programs = list.value;
    programs = JSON.parse(programs);
    console.log(programs);
    var areaWhitelist = [];
    for (const key in programs) {
        areaWhitelist.push(programs[key].program_name);
    }
    console.log(areaWhitelist);
    var area = document.getElementById('area_input');
    new Tagify(area,{
        enforceWhitelist:true,
        whitelist:areaWhitelist,
        dropdown:{
            maxItems:5,
            enabled:0
        },
    });
}
