var g_uuid = '';
$(function() {
	g_uuid = $.uuid();
});

function addSubLine(editor_typ,table_id){
	// console.log(editor_typ,table_id,table_item_vars,table_item_template);
	var id_pre = 'creator_';
	if (editor_typ==1){
		id_pre = 'modify_';
	}
	var newId = 0 - new Date().getTime();
	var newData = {_id:newId};
	var checkErr = false;
	$.each(table_item_vars,function(k,v){
		var value = $("#"+id_pre+v).val();
		if (table_item_must_vars[v] && value==""){
			checkErr = true;
			return;
		}
		newData[v] = value;
		$("#"+id_pre+v).val('');
	});
	if (checkErr){
		alert('请填写所有星号字段！');
		return;
	}
	table_all_data[newId] = newData;
	resetTable(table_id);
}

function removeSubLine(table_id,id){
	delete table_all_data[id];
	resetTable(table_id);
}

function resetTable(table_id){
	var _html = '';
	var totalGetting = 0;
	for(var k in table_all_data){      
       	console.log(typeof(table_all_data[k]));
       	if(typeof(table_all_data[k])=="function"){      
                
        }else{      
            _html += table_item_template.str_supplant(table_all_data[k]);
            totalGetting += parseFloat(table_all_data[k].allPrice);
        }      
    }
	$("#table_"+table_id).html(_html);
	console.log(table_all_data);
	$("#"+table_id).val(JSON.stringify(table_all_data));
	console.log($("#"+table_id).val());
	$("#creator_totalGetting").val(totalGetting);
	$("#creator_totalGetting").val(totalGetting);
	
}