class Modal{

    show(){
        $('#gen_modal').modal('show');
    }

    setBody(body){
        $('#grn_modal_res').html(body)
    }

    setFooter(footer){
        $('#gen_modal_footer').html(footer)
    }

    setTitle(title){
        $('#gen_modal_title').text(title)
    }

    setSize(size){
        if(size === 'lg'){
            $('#modal_d').addClass('modal-lg')
        } else {

        }
    }

    hide(){
        $('#gen_modal').modal('hide');
    }

} 

const mpop = new Modal()