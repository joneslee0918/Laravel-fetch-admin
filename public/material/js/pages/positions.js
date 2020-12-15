$(document).ready(function() {
    $("#position_table tbody").sortable({
      cursor: "move",
      placeholder: "sortable-placeholder",
      helper: function(e, tr)
      {
        var $originals = tr.children();
        var $helper = tr.clone();
        $helper.children().each(function(index)
        {
        $(this).width($originals.eq(index).width());
        });
        return $helper;
      }
    });
});
var getRow = function(index, id, title, edit, tr){
    if(edit){
        return `${tr ? `<tr id="position_${index}">` : ``}
                    <td id="position_title_${index}" value="${id}">
                        <input type="text" class="form-control" value="${title}" style="text-align:center">
                    </td>
                    <td>
                    <a rel="tooltip" class="btn btn-success btn-link btn-sm" data-original-title="Save" title="Save" onclick="addNewTheme(${index})">
                        <i class="material-icons">save</i>
                        <div class="ripple-container"></div>
                    </a>
                    <button rel="tooltip" type="button" class="btn btn-danger btn-link btn-sm" data-original-title="Delete" title="Delete" onclick="deleteTheme(${index})">
                        <i class="material-icons">close</i>
                        <div class="ripple-container"></div>
                    </button>
                    </td>
                    ${tr ? `</tr` : ``}`;
    }
    else{
        return `${tr ? `<tr id="position_${index}" value="${id}">` : ``}
                    <td id="position_title_${index}">${title}</td>
                    <td>
                    <a rel="tooltip" class="btn btn-success btn-link btn-sm" data-original-title="Edit" title="Edit" onclick="editTheme(${index})">
                        <i class="material-icons">edit</i>
                        <div class="ripple-container"></div>
                    </a>
                    <button rel="tooltip" type="button" class="btn btn-danger btn-link btn-sm" data-original-title="Delete" title="Delete" onclick="deleteTheme(${index})">
                        <i class="material-icons">close</i>
                        <div class="ripple-container"></div>
                    </button>
                    </td>
                ${tr ? `</tr` : ``}`;
    }
}
var addTheme = function(){
    $("#position_tbody").append( getRow(++total_index, 0, "", true, true) );
}
var addNewTheme = function(index){
    let id = $(`#position_${index}`).attr('value');
    let title = $(`#position_title_${index}`).find("input").val();
    $(`#position_${index}`).empty();
    $(`#position_${index}`).append(getRow(index, id, title, false, false));
}
var editTheme = function(index){
    let id = $(`#position_${index}`).attr('value');
    let title = $(`#position_title_${index}`).text();
    $(`#position_${index}`).empty();
    $(`#position_${index}`).append(getRow(index, id, title, true, false));
}
var deleteTheme = function(index){
    $(`#position_${index}`).remove();
}
var saveThemes = function(){
    if($(`#position_tbody`).find("input").length > 0)
        return showNotification('danger', "Please complete the editing...");
    let rows = $("#position_tbody").children();
    let position = [];
    for (let i = 0; i < rows.length; i++) {
        let element = $("#position_tbody").find(rows[i]);
        let id = parseInt(element.attr('value'));
        let text = element.find('td').get(0).innerText;
        position.push({index:i+1, id:id, title:text});
    };
console.log(position);
    $.ajax({
        url: 'users/positions',
        dataType: 'json',
        type: 'post',
        data: {positions:position},
        success: function (json, textStatus, jqXHR) {
            location.reload();
        },
        error: function (data, textStatus, jqXHR) {
            location.reload();
        }
    });
}
function showNotification(type, msg){
    $.notify({
        icon: "add_alert",
        message: msg

    },{
        type: type,
        timer: 3000,
        placement: {
            from: 'top',
            align: 'right'
        }
    });
}