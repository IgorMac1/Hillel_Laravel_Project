import './bootstrap'

$(document).ready(function (e) {
    if (window.FileReader) {

        $('#images').change(function () {
            let counter = 0, file;
            let template = `<div class="col-sm-12 d-flex justify-content-center align-items-center">
                                 <img src="__url__" class="card-img-top" style="max-width: 50%; margin: 0 auto; display: block;">
                             </div>`;

            $('.images-wrapper').html('');

            while (file = this.files[counter++]) {
                let reader = new FileReader();
                reader.onloadend = (() => function () {
                    let img = template.replace('__url__', this.result);
                    $('.images-wrapper').append(img);
                })(file);
                reader.readAsDataURL(file);
            }
        });

        $('#thumbnail').change(function () {
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#thumbnail-preview').attr('src', e.target.result)
            }
            reader.readAsDataURL(this.files[0]);
        });

    }
});
