
require(["jquery","underscore"], function ($, _){
	
	var convertOptgroups = function (array) {
		var ret = "";
		for(key in array)
		{
			ret = ret + '<optgroup label="' + key + '">';
			for(key2 in array[key])
			{
				ret = ret + '<option value="' + key2 + '">';
				ret = ret + array[key][key2];
				ret = ret + '</option>';
			}
			ret = ret + '</optgroup>';
		}
		return ret;
	
	};

	$(function (){
		$('#addgraph').click(function (){
			var temptext = $('#_t-graph-group').html();
			var optgroup1 = {"Labell":{"var1":"name1", "var2":"name2"},"Label2":{"var3":"name3", "var4":"name4"}};
			var optgroup2 = {"Labell":{"var1":"name1", "var2":"name2"},"Label2":{"var3":"name3", "var4":"name4"}};
			
			$('#graphs').append( _.template(temptext, {xaxis:convertOptgroups(optgroup1), yaxis:convertOptgroups(optgroup2)}) );
			
			return false;
		});
	});
});
