$('#add-image').click(function(){
    //je recupère le numero des futurs champs que je vais créer
    const index = $('#ad_images div.form-group').length;

    // je recupère le protype des entrées 
    const tmpl = $('#ad_images').data('prototype').replace(/__name__/g, index);

    //console.log(tmpl);
    //j'inject ce code au seinsn d la div
    $('#ad_images').append(tmpl);

    //Je gère le bouton supprimer
    handleDeleteButton();
});

function handleDeleteButton(){
    $('button[data-action="delete"]').click(function(){
        const target = this.dataset.target;
        console.log(target);
        $(target).remove();
    });
}

handleDeleteButton();