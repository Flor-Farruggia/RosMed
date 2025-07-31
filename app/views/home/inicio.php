<!DOCTYPE html>
<!--http://localhost/MVC-2024/public/home/inicio-->


<html lang="es">
	<head>
		<?=$head?>
		<title><?=$title?></title>
	</head>
	<body>
		<?=$nav?>
        <?=$home?>
    <main>
        <section class="section bg-white" id="app">
            <div class="container">
                <h2 class="text-xl text-primary bold mb-5">Sobre nuestra app</h2>
                <div class="row">
                    <div class="col-1">
                        <div class="line-app"></div>
                    </div>
                    <div class="col-10 col-md-10">
                        <h6 class="text-medium text-primary x-medium mb-4">
                            En el corazón de nuestra aplicación reside una filosofía simple pero poderosa:
                        </h6>
                        <p class="text-medium regular text-primary">
                            poner el control de tu salud en tus manos. Creemos en
                            empoderar a pacientes y médicos mediante la tecnología, eliminando barreras
                            en el acceso a información médica crucial. Nuestra plataforma está diseñada
                            para democratizar la atención médica, ofreciendo un acceso fácil y seguro a
                            historiales clínicos. Consideramos que la salud es un
                            derecho fundamental, y nuestra aplicación representa un avance hacia un
                            sistema de atención médica más eficiente y personalizado.
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <section class="section bg-primary" id="clientes">
            <div class="container">
                <h2 class="text-xl text-white bold mb-5 position-relative" style="z-index: 1;">
                    Sobre Nuestros Clientes
                    <div class="title-clientes d-none d-md-block"></div>
                </h2>
                <div class="row">
                    <div class="col-11 col-lg-6 mb-5">
                        <div class="card card-clientes bg-primary shadow-light h-100">
                            <div class="card-header text-center">
                                <h5 class="text-big medium mt-3">Pacientes</h5>
                            </div>
                            <div class="card-body px-4 text-center">
                                <p class="text-regular text-white">
                                    Imagina tener tu historial médico al alcance de tu mano, sin papeleo ni demoras.
                                    Nuestra aplicación te permite tomar el control de tu salud de manera sencilla.
                                    Accede a tu historial, consulta alergias y tratamientos previos en segundos.
                                    Descubre la libertad de cuidar de ti y tus seres queridos de manera más
                                    inteligente con nuestra aplicación. Tu salud, tu historial, tu control.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-11 col-lg-6 mb-5">
                        <div class="card card-clientes bg-primary shadow-light h-100">
                            <div class="card-header text-center">
                                <h5 class="text-big medium mt-3">Médicos</h5>
                            </div>
                            <div class="card-body px-4 text-center">
                                <p class="text-regular text-white">
                                    Nuestra aplicación es esencial para médicos que buscan una atención más eficiente.
                                    Olvídate de la búsqueda de expedientes y los historiales desordenados.
                                    Nuestra plataforma facilita un cuidado superior y promueve la colaboración entre
                                    profesionales de salud, permitiendo compartir información médica vital de manera segura.
                                    Únete a nosotros en la revolución de la atención médica y brinda a tus pacientes un servicio excepcional.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section bg-white" id="planes">
            <div class="container">
                <h2 class="text-xl text-dark bold mb-5 position-relative" style="z-index: 1;">
                    Nuestros planes
                    <div class="title-clientes title-planes d-none d-md-block"></div>
                </h2>
                <div class="row">
                    <div class="col-11 col-lg-4 mb-5">
                        <div class="card card-planes bg-white shadow-blue h-100">
                            <div class="card-header text-center">
                                <h5 class="text-big medium mt-3 text-primary">Gratuito Pacientes</h5>
                                <p class="text-x-medium x-medium price-num mt-3 text-primary">
                                    0
                                    <span class="text-medium medium">$</span>
                                </p>
                            </div>
                            <div class="card-body px-4 text-justify">
                                <p class="text-regular text-primary">
                                    Sin cansarnos de repetirlo, lo volvemos a decir, desde RosMed creemos que
                                    la medicina es un derecho y no un privilegio, por ello tenemos un plan gratuito
                                    que solo se diferencia en sus publicidades.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-11 col-lg-4 mb-5">
                        <div class="card card-planes bg-white shadow-blue h-100">
                            <div class="card-header text-center">
                                <h5 class="text-big medium mt-3 text-primary">Premium Pacientes</h5>
                                <p class="text-x-medium x-medium price-num mt-3 text-primary">
                                    2000
                                    <span class="text-medium medium">$</span>
                                </p>
                            </div>
                            <div class="card-body px-4 text-justify">
                                <p class="text-regular text-primary">
                                    Porque sabemos que a veces las publicidades son molestas
                                    es que ofrecemos un servicio pago libre de interrupciones,
                                    con diseño de uso fácil para cualquier persona que te ayude
                                    a tener tu información a tu alcance y conectar con tu médicos.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-11 col-lg-4 mb-5">
                        <div class="card card-planes bg-white shadow-blue h-100">
                            <div class="card-header text-center">
                                <h5 class="text-big medium mt-3 text-primary">Premium Médicos</h5>
                                <p class="text-x-medium x-medium price-num mt-3 text-primary">
                                    2500
                                    <span class="text-medium medium">$</span>
                                </p>
                            </div>
                            <div class="card-body px-4 text-justify">
                                <p class="text-regular text-primary">
                                    Porque sabemos cuando te importan tus pacientes y para que puedas darle
                                    el mejor servicio, te ayudamos a tener un contacto y acceso simple a su
                                    información contando con la mejor seguridad.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section bg-secondary pb-0">
            <div class="container">
                <h2 class="text-xl text-dark bold text-center mb-5">Dónde operamos actualmente:</h2>
            </div>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d107134.75304223062!2d-60.779041013568786!3d-32.95203820188479!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95b6539335d7d75b%3A0xec4086e90258a557!2sRosario%2C%20Santa%20Fe!5e0!3m2!1ses-419!2sar!4v1731503241536!5m2!1ses-419!2sar" width="100%" height="550" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </section>
        <section class="section bg-secondary"id="contacto">
            <div class="container">
                <h5 class="text-xl text-dark bold text-center my-5">Por favor, no dudes en contactarnos, te contestaremos en breve, gracias por comunicarte:</h5>
                <form class="form-home d-flex flex-column align-items-center justify-content-center py-5">
                        <label class="text-white text-x-medium x-medium mb-3" for="fName">Nombre y Apellido:</label>
                        <input class="input-home text-medium rounded-3 mb-3" type="text" id="Name" name="Name" required minlength="3"
                        maxlength="30" placeholder="Escriba su nombre y apellido">
                        <label class="text-white text-x-medium x-medium mb-3" for="email">E-mail:</label>
                        <input class="input-home text-medium rounded-3 mb-3" type="email" id="email" name="email" required minlength="3" placeholder="Escriba su email">
                        <label class="text-white text-x-medium x-medium mb-3" for="message-area">Deje su consulta:</label>
                        <textarea class="rounded-3 mb-3 text-medium" id="message-area" name="message-area" rows="10" cols="40" required minlength="20"
                        maxlength="120" placeholder="Por favor, escribe tus dudas aquí..."></textarea>
                        <button class="btn-white rounded-3 mb-5 text-medium x-medium py-2 px-3" type="button">Enviar</button>
                    </div>
                </form>
            </div>
        </section>
    </main>
		<?=$footer?>
		<script src="/public/js/main.js"></script>
		<script src="/public/js/jquery.js"></script>
	</body>
</html>