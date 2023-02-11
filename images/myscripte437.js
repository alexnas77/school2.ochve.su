(function($) { 
		$(document).ready(function(){
		  
             TypePolice();
                 
              $('#edit-submitted-stoimost').change(function(){
                    TypePolice();
                
                
              });   
           
               function TypePolice() {
                  $('#webform-client-form-374 fieldset').hide();     
                    val =  $('#edit-submitted-stoimost').val();
                    if (val == 'key1') {
                           
                         $('#webform-component-kasko').show();        
                    }
                    
                    if (val == 'key2') {
                        
                          $('#webform-component-osago').show();     
                    }
                    
                    if (val == 'key3') {
                          $('#webform-component-kasko').show();        
                          $('#webform-component-osago').show();     
                    }
            }
            
            
            
            
            $('.field-name-field-block-text .field-item p').hide();
            
            $('.field-name-field-block-text .field-item h5').click(function(){
                
                $('.field-name-field-block-text .field-item p').hide();
                $(this).parent('div').children('p').slideToggle(500);
                
            });
               
                              
       
            
    	})
 })(jQuery);
