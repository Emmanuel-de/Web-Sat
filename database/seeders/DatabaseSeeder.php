<?php

namespace Database\Seeders;

use App\Models\ModuloSat;
use App\Models\Noticia;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ModuloSatSeeder::class,
            NoticiaSeeder::class,
        ]);
    }
}


class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario de prueba persona física
        User::firstOrCreate(['rfc' => 'GOML850101ABC'], [
            'rfc'             => 'GOML850101ABC',
            'curp'            => 'GOML850101HTCMPL09',
            'nombre'          => 'Juan Carlos',
            'primer_apellido' => 'González',
            'segundo_apellido'=> 'Morales',
            'email'           => 'jcgonzalez@ejemplo.com',
            'telefono'        => '8671234567',
            'password'        => Hash::make('Password123'),
            'tipo'            => 'fisica',
            'activo'          => true,
        ]);

        // Usuario de prueba persona moral
        User::firstOrCreate(['rfc' => 'EMP850101XYZ'], [
            'rfc'             => 'EMP850101XYZ',
            'nombre'          => 'Empresa Demo S.A. de C.V.',
            'primer_apellido' => '',
            'email'           => 'empresa@ejemplo.com',
            'telefono'        => '8671234568',
            'password'        => Hash::make('Password123'),
            'tipo'            => 'moral',
            'activo'          => true,
        ]);
    }
}


class ModuloSatSeeder extends Seeder
{
    public function run(): void
    {
        $modulos = [
            [
                'nombre'    => 'SAT Hidalgo',
                'estado'    => 'Ciudad de México',
                'municipio' => 'Cuauhtémoc',
                'direccion' => 'Av. Hidalgo 77, Col. Guerrero, Alcaldía Cuauhtémoc, CDMX, C.P. 06300',
                'horario'   => 'Lunes a Viernes 8:00 - 21:00 hrs',
                'telefono'  => '55 5628 1354',
                'latitud'   => 19.4394,
                'longitud'  => -99.1464,
                'servicios' => ['RFC','EFIRMA','CIF','DECLARACIONES','DEVOLUCIONES'],
            ],
            [
                'nombre'    => 'SAT San Pedro Garza García',
                'estado'    => 'Nuevo León',
                'municipio' => 'San Pedro Garza García',
                'direccion' => 'Calzada del Valle 400 Ote. Col. del Valle, San Pedro Garza García, N.L., C.P. 66220',
                'horario'   => 'Lunes a Viernes 9:00 - 18:00 hrs',
                'telefono'  => '81 8153 1600',
                'latitud'   => 25.6574,
                'longitud'  => -100.3667,
                'servicios' => ['RFC','EFIRMA','CIF'],
            ],
            [
                'nombre'    => 'SAT Guadalajara',
                'estado'    => 'Jalisco',
                'municipio' => 'Guadalajara',
                'direccion' => 'Av. Vallarta 4150, Col. Camino Real, Guadalajara, Jal., C.P. 45040',
                'horario'   => 'Lunes a Viernes 8:30 - 20:00 hrs',
                'telefono'  => '33 3678 2300',
                'latitud'   => 20.6597,
                'longitud'  => -103.3496,
                'servicios' => ['RFC','EFIRMA','CIF','DECLARACIONES'],
            ],
            [
                'nombre'    => 'SAT Ciudad Victoria',
                'estado'    => 'Tamaulipas',
                'municipio' => 'Ciudad Victoria',
                'direccion' => 'Calle Matamoros 812, Col. Centro, Ciudad Victoria, Tams., C.P. 87000',
                'horario'   => 'Lunes a Viernes 9:00 - 17:00 hrs',
                'telefono'  => '83 4315 0000',
                'latitud'   => 23.7369,
                'longitud'  => -99.1411,
                'servicios' => ['RFC','EFIRMA','CIF'],
            ],
            [
                'nombre'    => 'SAT Monterrey Centro',
                'estado'    => 'Nuevo León',
                'municipio' => 'Monterrey',
                'direccion' => 'Zaragoza 1300 Sur, Col. Centro, Monterrey, N.L., C.P. 64000',
                'horario'   => 'Lunes a Viernes 8:00 - 20:00 hrs',
                'telefono'  => '81 8343 3800',
                'latitud'   => 25.6691,
                'longitud'  => -100.3098,
                'servicios' => ['RFC','EFIRMA','CIF','DECLARACIONES','DEVOLUCIONES'],
            ],
            [
                'nombre'    => 'SAT Puebla',
                'estado'    => 'Puebla',
                'municipio' => 'Puebla',
                'direccion' => '5 de Mayo 1702, Col. Centro Histórico, Puebla, Pue., C.P. 72000',
                'horario'   => 'Lunes a Viernes 9:00 - 18:30 hrs',
                'telefono'  => '22 2246 0000',
                'latitud'   => 19.0414,
                'longitud'  => -98.2063,
                'servicios' => ['RFC','EFIRMA','CIF'],
            ],
        ];

        foreach ($modulos as $modulo) {
            ModuloSat::firstOrCreate(
                ['nombre' => $modulo['nombre'], 'estado' => $modulo['estado']],
                array_merge($modulo, ['activo' => true])
            );
        }
    }
}


class NoticiaSeeder extends Seeder
{
    public function run(): void
    {
        $noticias = [
            [
                'titulo'    => 'Nuevo esquema de cancelación de CFDI a partir del 1 de enero 2025',
                'slug'      => 'nuevo-esquema-cancelacion-cfdi-2025',
                'resumen'   => 'El SAT informa sobre los cambios en el proceso de cancelación de comprobantes fiscales digitales, incluyendo nuevos plazos y requisitos para la aceptación del receptor.',
                'categoria' => 'empresas',
                'activo'    => true,
                'fecha'     => '2024-12-15',
                'autor'     => 'SAT',
            ],
            [
                'titulo'    => 'Prórroga para la Declaración Anual 2024 de personas físicas',
                'slug'      => 'prorroga-declaracion-anual-2024-personas-fisicas',
                'resumen'   => 'El Servicio de Administración Tributaria extiende el plazo para la presentación de la Declaración Anual del ejercicio 2024 para personas físicas hasta el 30 de mayo de 2025.',
                'categoria' => 'personas',
                'activo'    => true,
                'fecha'     => '2025-04-01',
                'autor'     => 'SAT',
            ],
            [
                'titulo'    => 'Actualización de catálogos del SAT para CFDI versión 4.0',
                'slug'      => 'actualizacion-catalogos-cfdi-4-0',
                'resumen'   => 'Se publican actualizaciones a los catálogos de Clave de Producto o Servicio, Clave de Unidad de Medida y Formas de Pago aplicables al CFDI versión 4.0.',
                'categoria' => 'empresas',
                'activo'    => true,
                'fecha'     => '2025-03-10',
                'autor'     => 'SAT',
            ],
        ];

        foreach ($noticias as $noticia) {
            Noticia::firstOrCreate(
                ['slug' => $noticia['slug']],
                $noticia
            );
        }
    }
}