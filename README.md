./vendor/bin/sail up

php artisan make:migration create_cidades_table --create=cidades

php artisan make:migration create_paciente_table --create=pacientes
php artisan make:migration create_medico_table --create=medicos
php artisan make:migration create_medico_paciente_table --create=medico_paciente

php artisan make:factory CidadesFactory
php artisan make:factory PacienteFactory
php artisan make:factory MedicoFactory
php artisan make:factory MedicoPacienteFactory

php artisan migrate
