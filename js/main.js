$(document).ready(function(){
    let files = [],
        filesNames = [],
        inputFile = $('input[type=file]'),
        inputName = $('#name')

    inputFile.on('change', function(){
        files.push(this.files[0])
        filesNames.push($(this).data('doc'))
    });

$('#send').on( 'click', function( event ){
    event.stopPropagation()
    event.preventDefault()

    $.each($('.important'), function(index, value){
        var label = '.' + $(value).attr('id')
        if($(value).val()){
            $(label).removeClass('error')
            $(label).addClass('success')
        } else {
            $(label).removeClass('success')
            $(label).addClass('error')
        }
    })
    if( files.length < 4 || inputName.val() == ''){
        Swal.fire(
            'Ошибка!',
            'Заполните все необходимые поля.',
            'error'
          )
        return
    } else {
        Swal.fire({
            allowOutsideClick: false,
            onOpen: () => {
                Swal.showLoading()
            }
        })
    }

    let data = new FormData()

    $.each( files, function( key, value ){
        data.append( key, value )
    });

    data.append('user_info', inputName.val())
    data.append('docNames', filesNames)
    
    $.ajax({
        url: './php/ajax.php',
        type: 'POST',
        data: data,
        cache: false,
        dataType: 'json',
        processData: false,
        contentType: false, 
        success: function( respond, status, jqXHR ){
            if(respond){
                Swal.fire({
                    title: 'Успешно!',
                    text: 'Ваши фото были отправлены на рассмотрение.',
                    icon: 'success'
                  }).then((result) => {
                      location.reload()
                  })
            }
        },
        error: function( jqXHR, status, errorThrown ){
            console.log(errorThrown);
            Swal.fire({
                title: 'Ошибка!',
                text: 'Попробуйте немного позже.',
                icon: 'error'
              })
        }

    });

});

let line = $('.header-shapes');
$(document).on('mousemove', function(e) {
  let x = e.clientX / window.innerWidth;
  let y = e.clientY / window.innerHeight;  
  line[0].style.transform = 'translate(+' + x * 50 + 'px, +' + y * 50 + 'px)';
  line[1].style.transform = 'translate(-' + x * 50 + 'px, +' + y * 50 + 'px)';
})

})