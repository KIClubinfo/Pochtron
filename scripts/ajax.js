function ajax_url(my_url, GET_args, callback_function, error_function)
{	
	$.ajax({
			url: my_url,
			data:GET_args,
			  cache: false,
			  dataType: "json",
			error : function(request, error) { error_function('400', 'Impossible de joindre le script', GET_args);   },
			success: function(data)
			{ 
				if (data.code_erreur=="0")
					callback_function(data.code_erreur, data.reponse, GET_args);
				else
				{
					error_function(data.code_erreur, data.reponse, GET_args); 
				}
			}
		  });
}