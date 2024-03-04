 // Add More
        
 function removeCloneTemplateRow(el){
    $(el).parents('.clone-template').remove();
}

var remove_combo_btn_html                                                   =   ''; 
var clone_template_id                                                       =   0;

function add_more(e, type, parent_class){
    
    var clone_template                                                      =   $(e).parents(parent_class).find('.clone-template');
    var last_clone_template_id                                              =   clone_template.last().attr('data-clone-template-id');
    var dublicate_clone_template                                            =   clone_template.first().clone();
    
    next_clone_template_id                                                  =   parseInt(last_clone_template_id) + 1;
                                    
    html_remove_current_clone_template_btn	                                = 	'<span class="remove-clone-template-row fa fa-trash" onclick="removeCloneTemplateRow(this)">X</span>';

    modifyCloneTemplate(dublicate_clone_template, next_clone_template_id, type);
    
    dublicate_clone_template.append(html_remove_current_clone_template_btn);
    clone_template.last().after(dublicate_clone_template);
}

function modifyCloneTemplate(dublicate_clone_template, clone_template_id, type){
    
    dublicate_clone_template.attr('data-clone-template-id', clone_template_id);
    dublicate_clone_template.find('input[name="product_id"]').remove();
    
    switch(type){
        case 'faq':
                dublicate_clone_template.find('.question').attr('name', "faq["+clone_template_id+"][question]").val('');
                dublicate_clone_template.find('.answer').attr('name', "faq["+clone_template_id+"][answer]").val('');
            break;
            
        case 'key_ingredients':
                dublicate_clone_template.find('img').remove();
                dublicate_clone_template.find('.images').attr('name', "key_ingredients["+clone_template_id+"][images]").val('');
                dublicate_clone_template.find('.heading').attr('name', "key_ingredients["+clone_template_id+"][heading]").val('');
                dublicate_clone_template.find('.description').attr('name', "key_ingredients["+clone_template_id+"][description]").val('');
            break;
    }
 
}
    
// End Add More