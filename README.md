<<<<<<< HEAD
# Módulo de Listas Negras (RBL) para Roundcube

Este módulo bloquea el acceso a Roundcube si la dirección IP del usuario está listada en listas negras (RBL).  
Si la IP está en una RBL, se muestra un mensaje de error con información sobre cómo desbloquearla.
Es una alternativa a la utilización de captcha o similares, podrías tener tu propia rbl y consultarla.

## Instalación

1. **Ubicar el módulo en la carpeta de plugins de Roundcube**  
   Copia la carpeta `rbl` dentro del directorio `plugins/` de tu instalación de Roundcube.  
   La estructura de archivos debe quedar así:

   ```
   plugins/
   ├── rbl/
   │   ├── config.inc.php
   │   ├── rbl.php
   ```

2. **Activar el módulo en Roundcube**  
   Edita el archivo de configuración principal de Roundcube `config/config.inc.php` y agrega `'rbl'` en la lista de plugins:

   ```php
   $config['plugins'] = array('rbl', /* otros plugins */);
   ```

3. **Configurar las listas negras**  
   - Edita el archivo `plugins/rbl/config.inc.php` para definir las RBLs que deseas consultar.  
   - Puedes agregar múltiples RBLs y definir mensajes personalizados con enlaces de deslistado.

## Funcionamiento

- Al iniciar sesión en Roundcube, el módulo consulta varias RBLs configuradas.
- Si la IP está en una lista negra, se bloquea el acceso con un código HTTP 403 Forbidden.
- Se muestra un mensaje con información específica de cada RBL en la que la IP está listada.
- Si la IP no está en una RBL, Roundcube se carga normalmente.

## Configuración

Para agregar nuevas listas negras o modificar mensajes de error, edita `config.inc.php`.  
Ejemplo de una nueva RBL:

```php
$config['rbls']['barracudacentral.org'] = array(
    'listed_values' => array('127.0.0.2'),
    'message' => "Su IP está en la lista de Barracuda. 
                  Para deslistarse, visite: <a href='https://barracudacentral.org' target='_blank'>Barracuda</a>."
);
```

## Solución de problemas

Si el módulo no funciona como se espera, sigue estos pasos para depurar el problema:

1. **Verificar los permisos de los archivos**  
   Asegúrate de que los archivos `rbl.php` y `config.inc.php` tengan permisos adecuados para ser leídos por el servidor web.

2. **Revisar los logs de errores**  
   Consulta los registros de errores de PHP y Roundcube (rbl.log) para ver si hay mensajes que indiquen problemas con la ejecución del módulo.

3. **Probar la conectividad con las RBLs**  
   Desde la línea de comandos, puedes probar manualmente si una IP está listada en una RBL con el siguiente comando:

   ```
   dig +short 1.2.3.4.dnsbl-1.uceprotect.net
   ```

   Si la respuesta devuelve una dirección como 127.0.0.2, significa que la IP está listada.

4. **Desactivar temporalmente el módulo**  
   Si necesitas desactivar el módulo para pruebas, edita `config/config.inc.php` de Roundcube y elimina `'rbl'` del array de plugins.

## Notas adicionales

- Este módulo no afecta la funcionalidad de Roundcube si la IP no está en una RBL.
- La consulta a las RBLs se realiza en tiempo real cada vez que un usuario intenta acceder a Roundcube.
- Puedes agregar tantas RBLs como necesites en `config.inc.php`.

## Créditos

Desarrollado para integrarse con Roundcube y mejorar la seguridad del acceso mediante listas negras.  
=======
# roundcube-rbl
Plugin para consultar una rbl en roundcube.
>>>>>>> ca590a8a79c5c944beed015525d7486bab2a8367
