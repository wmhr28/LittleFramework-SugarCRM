<?php
/**
 * Description of after_ui_frame
 *
 * @author Mauricio Herrera <mwherrera@plus-projects.com>
 * @date 2/05/2014
 *
 */
if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

class after_ui_frame {

    function validarGiftCard($event, $arguments) {
        if ($_REQUEST['action'] == 'index') {
            $resultado = " <script type='text/JavaScript'>"
                    . " $('.quickEdit').remove();"
                    . " $('.paginationActionButtons').remove();"                    
                    . " $('input[name=\"mass[]\"]').remove();"                    
                    
                    . " </script>";
            echo $resultado;
        }
        if ($_REQUEST['action'] == 'EditView') {
            $resultado = "
                        <div id='num_tarjeta_val' class='required' style='display:none;'></div>                        
                        <div id='contact_val' class='required' style='display:none;'></div>
                        <script type='text/javascript'>                               
                        
                            function des_habilitar_save(valor){
                                jQuery('#save_and_continue').prop('disabled',valor);
                                jQuery('#SAVE_HEADER').prop('disabled',valor);
                                jQuery('#SAVE_FOOTER').prop('disabled',valor);  
                            }
                            function limpiar_deshabilitar(){
                                jQuery('#num_tarjeta_gift_c').val('');
                                jQuery('#num_tarjeta_val').hide();   
                                des_habilitar_save(false);
                            }
                            
                            $(document).ready(function(){
                                $( '#save_and_continue' ).remove();
                                $( '#SAVE_HEADER' ).attr( 'onclick','');
                                $('#SAVE_HEADER').get(0).type = 'button';                                 
                                $( '#SAVE_FOOTER' ).attr( 'onclick','');
                                $('#SAVE_FOOTER').get(0).type = 'button';                                 
                                $( '#SAVE_HEADER' ).click(function( event ) {    
                                    eventoclick();
                                });                                                                
                                $( '#SAVE_FOOTER' ).click(function( event ) {    
                                    eventoclick();
                                });                                                                
                            });
                            function eventoclick(){                                                               
                                if ($('#contacts_cases_1contacts_ida').val()!='') {  
                                    $('#contact_val').hide();   
                                    newSubmit();
                                } else{
                                    $('#contact_val').show();
                                    $('#contact_val').html('Por favor seleccione un Contacto');
                                }
                            }
                            function newSubmit(){                                                               
                                var _form = document.getElementById('EditView');
                                _form.action.value='Save'; 
                                if(check_form('EditView'))
                                    SUGAR.ajaxUI.submitForm(_form);
                                return false;                                                             
                            }
                            var padre = document.getElementById('num_tarjeta_gift_c').parentNode;
                            padre.appendChild(document.getElementById('num_tarjeta_val'));
                            padre = document.getElementById('contacts_cases_1_name').parentNode;
                            padre.appendChild(document.getElementById('contact_val'));                            
                            var texto=$('#contacts_cases_1_name_label').html();
                            $('#contacts_cases_1_name_label').html(texto+\"<span id='newreq' class='required'>*</span>\");
                            jQuery('#num_tarjeta_gift_c').blur(function() {                                
                                var RegExPattern = /^[0-9]{16}$/; 
                                var campo=document.getElementById('num_tarjeta_gift_c');
                                if ((campo.value.match(RegExPattern))) {
                                    des_habilitar_save(false);
                                    jQuery('#num_tarjeta_val').hide();  
                                } else {
                                    jQuery('#num_tarjeta_val').show();
                                    jQuery('#num_tarjeta_val').html('El Número de Gift Card no es válido');
                                    des_habilitar_save(true); 
                                }
                            });                               
                            jQuery('#tipo_caso_c').change(function() { 
                                limpiar_deshabilitar();
                            }); 
                            jQuery('#subtipo_caso_c').change(function() { 
                                limpiar_deshabilitar();  
                            }); 
                        </script>";
            echo $resultado;
        }
        if ($_REQUEST['action'] == 'DetailView') {
            $resultado = "<script type='text/javascript'>
                        $('#detail_header_action_menu').hide();
                        </script>";
            echo $resultado;
        }
    }

}

?>