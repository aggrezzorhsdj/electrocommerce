import 'bootstrap-select';
import jQuery from 'jquery';

export function selectWatch(selector, cb) {
    jQuery(document).ready(function($){
        $(selector).on("changed.bs.select", function(e, clickedIndex, isSelected, previousValue) {
            const selected = [];
            $(this).find("option:selected").each(function(key,value){
                selected.push(value.innerHTML);
            });
            cb(e, selected);
        })
    });
}
