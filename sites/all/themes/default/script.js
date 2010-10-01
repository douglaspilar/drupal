$(function(){
   var error_class ='error';
   $('.page-criar-mensagem #node-form').validate({
       rules:{
         "field_nome_remetente[0][value]": { required:true },
         "field_email_remetente[0][email]":{ required:true, email:true},
         "field_medico_destinatario[0][nid][nid]":{ required:true},
         "title":{required:true},
         "field_li_politica[value]":{required:true}
       },
       messages:{
         "field_nome_remetente[0][value]": { required: "" },
         "field_email_remetente[0][email]":{ required: "" , email:"" },
         "field_medico_destinatario[0][nid][nid]":{ required: "" },
         "title": { required: "" },
         "field_li_politica[value]":{required:""}
       },
       highlight: function(element, errorClass){
          if( $(element).hasClass('form-checkbox') ){
             $(element).parent().addClass(error_class);
          }else{
             $(element).addClass(error_class);
          }
       },
       unhighlight: function(element, errorClass){
          if( $(element).hasClass('form-checkbox') ){
             $(element).parent().removeClass(error_class);
          }else{
             $(element).removeClass(error_class);
          }
       },
       errorPlacement: function(error, element){
          error.appendTo(element.prev());
       },
       errorElement: 'strong'
   });
});

