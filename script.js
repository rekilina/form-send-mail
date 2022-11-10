// Проверяем, что документ загружен
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form');
    form.addEventListener('submit', formSend);
    

    async function formSend(e) {
        e.preventDefault();
        let error = formValidate(form);
        
        let formData = new FormData(form);
        // append or formData['image'] = ..... ?
        formData.append('image', formImage.files[0]);


        if (error === 0) {
            // Процесс отправки вормы может занимать некоторое время
            // Поэтому как только мы убедились, что ошибок в отправляемой форме нет,
            // мы сразу добавляем форме класс _sending
            // Чтобы во время отправки пользователь ничего не мог делать
            form.classList.add('_sending');
            // AJAX
            let response = await fetch('sendmail.php', {
                method: 'POST',
                body: formData
            });
            if (response.ok) {
                // код sendmail.php возвращает json
                // let result0 = await response;
                // let result = await response.json();
                // alert(result.message);
                formPreview.innerHTML = ''; // очищаем превью изображения
                form.reset(); // очищаем все поля всей формы
                // Убираем класс _sending после отправки
                form.classList.remove('_sending');
            } else {
                alert('Error while sending form');
                // Убираем класс _sending после ошибки отправки
                form.classList.remove('_sending');
            }
        } else {
            alert('Введите обязательные поля')
        }
    }

    function formValidate(form) {
        let error = 0;
        let formReq = document.querySelectorAll('._req');

        //for (let index = 0; index < formReq.length; index++) {
        for (let input of formReq) {
            // const input = formReq[index];
            formRemoveError(input);
            if(input.classList.contains('user_email')) {
                if(emailTest(input)) {
                    formAddError(input);
                    error++;
                }
            } else if(input.getAttribute("type") == "checkbox" && input.checked === false) {
                formAddError(input);
                error++;
            } else {
                if(input.value == '') {
                    formAddError(input);
                    error++;
                }
            }
        }
        return error;
    }
    function formAddError(input) {
        input.parentElement.classList.add('_error');
        input.classList.add('_error');
    }
    function formRemoveError(input) {
        input.parentElement.classList.remove('_error');
        input.classList.remove('_error');
    }
    function emailTest(input) {
        return !/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.w{2,8})+$/.text(input.value);
    }

    // Получаем input file в переменную
    const formImage = document.getElementById('formImage');
    const formPreview = document.getElementById('formPreview');

    formImage.addEventListener('change', () => {
        uploadFile(formImage.files[0]);
    })
    function uploadFile(file) {
        // проверяем тип файла
        if(!['image/jpeg', 'image/png', 'image/gif'].includes(file.type)) {
            alert('Разрешены только изображения');
            formImage.value = '';
            return;
        }
        // проверяем размер файла < 2 Mb
        if (file.size > 2 * 1024 * 1024) {
            alert('Размер файла не должен превышать 2 Мб');
            return;
        }
        // Если все хорошо
        var reader = new FileReader();
        reader.onload = function(e) {
            formPreview.innerHTML = `<img src="${e.target.result}" alt="Image File">`;
        };
        reader.onerror = function(e) {
            alert('Error while loading file');
        };
        reader.readAsDataURL(file);
    }
})
