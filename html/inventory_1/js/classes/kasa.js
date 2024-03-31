class Kasa {
    alert(icon, message) {
        Swal.fire({
            icon: icon,
            title:`${icon.toUpperCase()}`,
            text: message,
            timer: 3000,
            showConfirmButton: false,
            timerProgressBar: true,
            allowOutsideClick: false,
            onBeforeOpen: () => {
                Swal.showLoading();
                var b = Swal.getHtmlContainer().querySelector('b');
                b.textContent = Swal.getTimerLeft();

                var timerInterval = setInterval(() => {
                    b.textContent = Swal.getTimerLeft();
                }, 100);

                Swal.stopTimer();
                setTimeout(() => {
                    Swal.resumeTimer();
                    Swal.hideLoading();
                    clearInterval(timerInterval);
                }, 100);
            }
        });
    }

    success(message) {
        this.alert('success', message);
    }

    error(message) {
        this.alert('error', message);
    }

    info(message) {
        this.alert('info', message);
    }

    warning(message) {
        this.alert('warning', message);
    }

    question(message) {
        this.alert('question', message);
    }

    html(message){
        Swal.fire({
            html:message
        })
    }

    response(x) {
        if(x['code'] === 200 || x['status_code'] === 200){
            this.success(x['message'])
        } else {
            if(x['message']){
                this.error(x['message'])
            } else {
                this.error("No Valid Response Received")
            }
        }
    }
}

const kasa = new Kasa()