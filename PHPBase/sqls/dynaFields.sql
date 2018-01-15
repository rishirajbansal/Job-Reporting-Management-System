/* Licensed To: ThoughtExecution & 9sistemes
* Authored By: Rishi Raj Bansal
* Developed in: Jul-Aug-Sep 2016
 * ===========================================================================
 * This is FULLY owned and COPYRIGHTED by ThoughtExecution
 * This code may NOT be RESOLD or REDISTRUBUTED under any circumstances, and is only to be used with this application
 * Using the code from this application in another application is strictly PROHIBITED and not PERMISSIBLE
 * ===========================================================================
*/
/**
 * Author:  Rishi Raj
 * Created: 5 Aug, 2016
 */

/**
 * Prodcut Management SQLs
 */
INSERT INTO prdts_fields (name_en, name_es, description_en, description_es, html_name, html_type, html_list_values_en, html_list_values_es, html_validations, html_validations_messages_en, html_validations_messages_es, icon, recom, created_on)   
VALUES('Product Name', 'Nombre del producto', 'This control is used to assign the Product name', 'Este control se utiliza para asignar el nombre del producto', 'prd_name', 'Text Box', '', '', 'required:true|alphanumeric:true', 'required:"Product Name is required"|alphanumeric:"Sorry! colon(:) Pipe(|)  comma(,) semicolon(;) greator then(>) lesser then(<)  are not allowed"', 'required:"El nombre es necesario Producto"|alphanumeric:"¡Lo siento! dos puntos (:) vertical (|) coma (,) punto y coma (;) mayor que (>) menor que (<) no están permitidos"', 'fa-caret-right', 1, NOW());

INSERT INTO prdts_fields (name_en, name_es, description_en, description_es, html_name, html_type, html_list_values_en, html_list_values_es, html_validations, html_validations_messages_en, html_validations_messages_es, icon, recom, created_on)  
VALUES('Product Code', 'Código de producto', 'This control is used to assign the Product Code', 'Este control se utiliza para asignar el código de producto', 'prd_code', 'Text Box', '', '', 'alphanumeric:true', 'alphanumeric:"Sorry! colon(:) Pipe(|)  comma(,) semicolon(;) greator then(>) lesser then(<)  are not allowed"', 'alphanumeric:"¡Lo siento! dos puntos (:) vertical (|) coma (,) punto y coma (;) mayor que (>) menor que (<) no están permitidos"', 'fa-caret-right', 0, NOW());

INSERT INTO prdts_fields (name_en, name_es, description_en, description_es, html_name, html_type, html_list_values_en, html_list_values_es, html_validations, html_validations_messages_en, html_validations_messages_es, icon, recom, created_on)  
VALUES('Product Description', 'Descripción del producto', 'This control is used to provide the Product Description', 'Este control se utiliza para introducir la descripción del producto', 'prd_desc', 'Textarea', '', '', 'alphanumeric:true', 'alphanumeric:"Sorry! colon(:) Pipe(|)  comma(,) semicolon(;) greator then(>) lesser then(<)  are not allowed"', 'alphanumeric:"¡Lo siento! dos puntos (:) vertical (|) coma (,) punto y coma (;) mayor que (>) menor que (<) no están permitidos"', 'fa-caret-right', 0, NOW());

INSERT INTO prdts_fields (name_en, name_es, description_en, description_es, html_name, html_type, html_list_values_en, html_list_values_es, html_validations, html_validations_messages_en, html_validations_messages_es, icon, recom, created_on)  
VALUES('Product Family', 'Familia de productos', 'This control is used to provide the Product Family', 'Este control se utiliza para introducir  la familia de productos', 'prd_family', 'Text Box', '', '', 'alphanumeric:true', 'alphanumeric:"Sorry! colon(:) Pipe(|)  comma(,) semicolon(;) greator then(>) lesser then(<)  are not allowed"', 'alphanumeric:"¡Lo siento! dos puntos (:) vertical (|) coma (,) punto y coma (;) mayor que (>) menor que (<) no están permitidos"', 'fa-caret-right', 0, NOW());

INSERT INTO prdts_fields (name_en, name_es, description_en, description_es, html_name, html_type, html_list_values_en, html_list_values_es, html_validations, html_validations_messages_en, html_validations_messages_es, icon, recom, created_on)  
VALUES('Measurement Unit', 'Measurement Unit', 'This control is used to specify the measurement unit of the product', 'Este control se utiliza para especificar la unidad de medida del producto', 'prd_munit', 'Text Box', '', '', 'alphanumeric:true', 'alphanumeric:"Sorry! colon(:) Pipe(|)  comma(,) semicolon(;) greator then(>) lesser then(<)  are not allowed"', 'alphanumeric:"¡Lo siento! dos puntos (:) vertical (|) coma (,) punto y coma (;) mayor que (>) menor que (<) no están permitidos"', 'fa-flask', 0, NOW());


/**
 * Tasks Management SQLs
 */
INSERT INTO tsks_fields (name_en, name_es, description_en, description_es, html_name, html_type, html_list_values_en, html_list_values_es, html_validations, html_validations_messages_en, html_validations_messages_es, icon, recom, created_on)  
VALUES('Task Name', 'Nombre de la tarea', 'This control is used to assign the Task name', 'Este control se utiliza para asignar el nombre de tareas', 'tsk_name', 'Text Box', '', '', 'required:true|alphanumeric:true', 'required:"Task Name is required"|alphanumeric:"Sorry! colon(:) Pipe(|)  comma(,) semicolon(;) greator then(>) lesser then(<)  are not allowed"', 'required:"El nombre es necesario Tarea"|alphanumeric:"¡Lo siento! dos puntos (:) vertical (|) coma (,) punto y coma (;) mayor que (>) menor que (<) no están permitidos"', 'fa-caret-right', 1, NOW());

INSERT INTO tsks_fields (name_en, name_es, description_en, description_es, html_name, html_type, html_list_values_en, html_list_values_es, html_validations, html_validations_messages_en, html_validations_messages_es, icon, recom, created_on)  
VALUES('Task Type', 'Tipo de tarea', 'This control is used to configure the tasks types applicable to the organisation business', 'Este control se utiliza para configurar los tipos de tareas aplicables a la Empresa empresarial', 'tsk_type', 'Combo Box', 'Scheduled|Incidence|Job Report|Start|Stop|Restart|Delivery|Load', 'Programado|Incidencia|Informe De Trabajo|Inicio|Pare|Reinicie|Entrega|Carga', '', '', '', '', 0, NOW());

INSERT INTO tsks_fields (name_en, name_es, description_en, description_es, html_name, html_type, html_list_values_en, html_list_values_es, html_validations, html_validations_messages_en, html_validations_messages_es, icon, recom, created_on)  
VALUES('Task Description', 'Descripción de la tarea', 'This control is used to provide the Task Description', 'Este control se utiliza para introducir  la Tarea Descripción', 'tsk_desc', 'Textarea', '', '', 'alphanumeric:true', 'alphanumeric:"Sorry! colon(:) Pipe(|)  comma(,) semicolon(;) greator then(>) lesser then(<)  are not allowed"', 'alphanumeric:"¡Lo siento! dos puntos (:) vertical (|) coma (,) punto y coma (;) mayor que (>) menor que (<) no están permitidos"', '', 0, NOW());

INSERT INTO tsks_fields (name_en, name_es, description_en, description_es, html_name, html_type, html_list_values_en, html_list_values_es, html_validations, html_validations_messages_en, html_validations_messages_es, icon, recom, created_on)  
VALUES('Assigned Worker Name', 'Nombre asignado Trabajador', 'This control is used to set the worker name responsible for completing the task', 'Este control se utiliza para establecer el nombre del trabajador responsable de completar la tarea', 'tsk_worker', 'Text Box', '', '', 'alphanumeric:true', 'alphanumeric:"Sorry! colon(:) Pipe(|)  comma(,) semicolon(;) greator then(>) lesser then(<)  are not allowed"', 'alphanumeric:"¡Lo siento! dos puntos (:) vertical (|) coma (,) punto y coma (;) mayor que (>) menor que (<) no están permitidos"', 'fa-caret-right', 0, NOW());

INSERT INTO tsks_fields (name_en, name_es, description_en, description_es, html_name, html_type, html_list_values_en, html_list_values_es, html_validations, html_validations_messages_en, html_validations_messages_es, icon, recom, created_on)  
VALUES('Is Dependent on other task', 'Depende de otras tareas', 'This control is used to determine if the task is dependent on the completion of other task', 'Este control se utiliza para determinar si la tarea depende de la realización de otra tarea', 'tsk_depend', 'Checkbox', '', '', '', '', '', '', 0, NOW());

INSERT INTO tsks_fields (name_en, name_es, description_en, description_es, html_name, html_type, html_list_values_en, html_list_values_es, html_validations, html_validations_messages_en, html_validations_messages_es, icon, recom, created_on)  
VALUES('Created On', 'Creado en', 'This control indicates the date when the task was created', 'Este control indica la fecha en que se creó la tarea', 'tsk_createdate', 'Date', '', '', '', '', '', 'fa-calendar', 0, NOW());

INSERT INTO tsks_fields (name_en, name_es, description_en, description_es, html_name, html_type, html_list_values_en, html_list_values_es, html_validations, html_validations_messages_en, html_validations_messages_es, icon, recom, created_on)  
VALUES('Status', 'Estado', 'This control is used to set the work status of the task', 'This control is used to set the work status of the task', 'tsk_status', 'Combo Box', 'Open|Pending|Waiting|Pending - Reopened|Completed', 'Abierto|Pendientes|A la espera|Pendientes - reabierto|Completeda', '', '', '', '', 1, NOW());



/**
 * Workers Management SQLs
 */
INSERT INTO wrks_fields (name_en, name_es, description_en, description_es, html_name, html_type, html_list_values_en, html_list_values_es, html_validations, html_validations_messages_en, html_validations_messages_es, icon, recom, created_on)  
VALUES('Worker Name', 'Nombre del Trabajador', 'This control is used to assign the Worker name', 'Este control se utiliza para asignar el nombre del trabajador', 'wrk_name', 'Text Box', '', '', 'required:true|alphanumeric:true', 'required:"Worker Name is required"|alphanumeric:"Sorry! colon(:) Pipe(|)  comma(,) semicolon(;) greator then(>) lesser then(<)  are not allowed"', 'required:"El nombre es necesario trabajador"|alphanumeric:"¡Lo siento! dos puntos (:) vertical (|) coma (,) punto y coma (;) mayor que (>) menor que (<) no están permitidos"', 'fa-caret-right', 1, NOW());

INSERT INTO wrks_fields (name_en, name_es, description_en, description_es, html_name, html_type, html_list_values_en, html_list_values_es, html_validations, html_validations_messages_en, html_validations_messages_es, icon, recom, created_on)  
VALUES('Worker Type', 'Trabajador Tipo', 'This control is used to configure the Worker types applicable to the organisation business', 'Este control se utiliza para configurar los tipos de trabajadores aplicables a la Empresa', 'wrk_type', 'Combo Box', 'Plumber|Mechanic|Engineer|Driver|Carpenter|Painter|Garden Man|Car Repair', 'Fontanero|Mecánico|Ingeniero|Conductor|Carpintero|Pintor|Jardín Hombre|Reparación De Autos', '', '', '', '', 0, NOW());

INSERT INTO wrks_fields (name_en, name_es, description_en, description_es, html_name, html_type, html_list_values_en, html_list_values_es, html_validations, html_validations_messages_en, html_validations_messages_es, icon, recom, created_on)  
VALUES('Worker Phone', 'Trabajador de teléfono', 'This control is used to assign the Worker Phone', 'Este control se utiliza para asignar el teléfono Trabajador', 'wrk_phone', 'Text Box', '', '', 'alphanumeric:true', 'alphanumeric:"Sorry! colon(:) Pipe(|)  comma(,) semicolon(;) greator then(>) lesser then(<)  are not allowed"', 'alphanumeric:"¡Lo siento! dos puntos (:) vertical (|) coma (,) punto y coma (;) mayor que (>) menor que (<) no están permitidos"', 'fa-phone', 0, NOW());

INSERT INTO wrks_fields (name_en, name_es, description_en, description_es, html_name, html_type, html_list_values_en, html_list_values_es, html_validations, html_validations_messages_en, html_validations_messages_es, icon, recom, created_on)  
VALUES('Worker Email', 'Trabajador de correo electrónico', 'This control is used to assign the Worker Email', 'Este control se utiliza para asignar el trabajador Correo electrónico', 'wrk_email', 'Text Box', '', '', 'required:true|email:true|alphanumeric:true', 'required:"Worker Email is required"|email:"Email address must be in the format of name@domain.com"|alphanumeric:"Sorry! colon(:) Pipe(|)  comma(,) semicolon(;) greator then(>) lesser then(<)  are not allowed"', 'required:"Se requiere Trabajador de correo electrónico"|email:"Dirección de correo electrónico debe estar en el formato de name@domain.com"|alphanumeric:"¡Lo siento! dos puntos (:) vertical (|) coma (,) punto y coma (;) mayor que (>) menor que (<) no están permitidos"', 'fa-envelope', 0, NOW());


/**
 * Customer Management SQLs
 */
INSERT INTO cstmrs_fields (name_en, name_es, description_en, description_es, html_name, html_type, html_list_values_en, html_list_values_es, html_validations, html_validations_messages_en, html_validations_messages_es, icon, recom, created_on)  
VALUES('Customer Name', 'Nombre del Cliente', 'This control is used to assign the Customer name', 'Este control se utiliza para asignar el nombre del cliente', 'cstmr_name', 'Text Box', '', '', 'required:true|alphanumeric:true', 'required:"Customer Name is required"|alphanumeric:"Sorry! colon(:) Pipe(|)  comma(,) semicolon(;) greator then(>) lesser then(<)  are not allowed"', 'required:"El nombre es necesario Cliente"|alphanumeric:"¡Lo siento! dos puntos (:) vertical (|) coma (,) punto y coma (;) mayor que (>) menor que (<) no están permitidos"', 'fa-caret-right', 1, NOW());

INSERT INTO cstmrs_fields (name_en, name_es, description_en, description_es, html_name, html_type, html_list_values_en, html_list_values_es, html_validations, html_validations_messages_en, html_validations_messages_es, icon, recom, created_on)  
VALUES('Address', 'Dirección', 'This control is used to assign the address', 'Este control se utiliza para asignar la dirección', 'cstmr_add', 'Text Box', '', '', 'alphanumeric:true', 'alphanumeric:"Sorry! colon(:) Pipe(|)  comma(,) semicolon(;) greator then(>) lesser then(<)  are not allowed"', 'alphanumeric:"¡Lo siento! dos puntos (:) vertical (|) coma (,) punto y coma (;) mayor que (>) menor que (<) no están permitidos"', 'fa-caret-right', 0, NOW());

INSERT INTO cstmrs_fields (name_en, name_es, description_en, description_es, html_name, html_type, html_list_values_en, html_list_values_es, html_validations, html_validations_messages_en, html_validations_messages_es, icon, recom, created_on)  
VALUES('Phone', 'Teléfono', 'This control is used to provide the phone', 'Este control se utiliza para introducir el teléfono', 'cstmr_phone', 'Text Box', '', '', '', 'alphanumeric:"Sorry! colon(:) Pipe(|)  comma(,) semicolon(;) greator then(>) lesser then(<)  are not allowed"', 'alphanumeric:"¡Lo siento! dos puntos (:) vertical (|) coma (,) punto y coma (;) mayor que (>) menor que (<) no están permitidos"', 'fa-phone', 0, NOW());

INSERT INTO cstmrs_fields (name_en, name_es, description_en, description_es, html_name, html_type, html_list_values_en, html_list_values_es, html_validations, html_validations_messages_en, html_validations_messages_es, icon, recom, created_on)  
VALUES('Email', 'Correo electrónico', 'This control is used to assign the email', 'Este control se utiliza para asignar el correo electrónico', 'cstmr_email', 'Text Box', '', '', 'email:true|alphanumeric:true', 'email:"Email address must be in the format of name@domain.com"|alphanumeric:"Sorry! colon(:) Pipe(|)  comma(,) semicolon(;) greator then(>) lesser then(<)  are not allowed"', 'email:"Dirección de correo electrónico debe estar en el formato de name@domain.com"|alphanumeric:"¡Lo siento! dos puntos (:) vertical (|) coma (,) punto y coma (;) mayor que (>) menor que (<) no están permitidos"', 'fa-envelope', 0, NOW());

INSERT INTO cstmrs_fields (name_en, name_es, description_en, description_es, html_name, html_type, html_list_values_en, html_list_values_es, html_validations, html_validations_messages_en, html_validations_messages_es, icon, recom, created_on)  
VALUES('Customer Code', 'Código de Cliente', 'This control is used to provide the Customer code', 'Este control se utiliza para introducir el código de cliente', 'cstmr_code', 'Text Box', '', '', 'alphanumeric:true', 'alphanumeric:"Sorry! colon(:) Pipe(|)  comma(,) semicolon(;) greator then(>) lesser then(<)  are not allowed"', 'alphanumeric:"¡Lo siento! dos puntos (:) vertical (|) coma (,) punto y coma (;) mayor que (>) menor que (<) no están permitidos"', 'fa-caret-right', 0, NOW());


/**
 * Report Structure Configuration SQLs
 */
INSERT INTO rpts_fields (name_en, name_es, description_en, description_es, html_name, html_type, html_list_values_en, html_list_values_es, html_validations, html_validations_messages_en, html_validations_messages_es, icon, recom, created_on)  
VALUES('Service Date', 'Fecha de Servicio', 'This control is used to provide the date on which the service is provided to the customer', 'Este control se utiliza para introducir la fecha en que se presta el servicio al cliente', 'rpt_sdate', 'Date', '', '', '', '', '', 'fa-calendar', 1, NOW());

INSERT INTO rpts_fields (name_en, name_es, description_en, description_es, html_name, html_type, html_list_values_en, html_list_values_es, html_validations, html_validations_messages_en, html_validations_messages_es, icon, recom, created_on)  
VALUES('Service Start Time', 'Inicio del Servicio Horario', 'This control is used to provide the time on which the service is started', 'Este control se utiliza para introducir el tiempo en que se inicia el servicio', 'rpt_sstime', 'Time', '', '', '', '', '', 'fa-clock-o', 1, NOW());

INSERT INTO rpts_fields (name_en, name_es, description_en, description_es, html_name, html_type, html_list_values_en, html_list_values_es, html_validations, html_validations_messages_en, html_validations_messages_es, icon, recom, created_on)  
VALUES('Service End Time', 'Servicio de Hora de Finalización', 'This control is used to provide the time on which the service is finished', 'Este control se utiliza para introducir el tiempo en que se terminó el servicio', 'rpt_setime', 'Time', '', '', '', '', '', 'fa-clock-o', 1, NOW());

INSERT INTO rpts_fields (name_en, name_es, description_en, description_es, html_name, html_type, html_list_values_en, html_list_values_es, html_validations, html_validations_messages_en, html_validations_messages_es, icon, recom, created_on)  
VALUES('Insurance', 'Seguro', 'This control is used to provide the insurance name', 'Este control se utiliza para introducir el nombre de seguro', 'rpt_insu', 'Text Box', '', '', 'alphanumeric:true', 'alphanumeric:"Sorry! colon(:) Pipe(|)  comma(,) semicolon(;) greator then(>) lesser then(<)  are not allowed"', 'alphanumeric:"¡Lo siento! dos puntos (:) vertical (|) coma (,) punto y coma (;) mayor que (>) menor que (<) no están permitidos"', 'fa-caret-right', 0, NOW());

INSERT INTO rpts_fields (name_en, name_es, description_en, description_es, html_name, html_type, html_list_values_en, html_list_values_es, html_validations, html_validations_messages_en, html_validations_messages_es, icon, recom, created_on)  
VALUES('Signer', 'Firmante', 'This control is used to provide the name of the person who signs the report', 'Este control se utiliza para introducir el nombre de la persona que firma el informe', 'rpt_signer', 'Text Box', '', '', 'alphanumeric:true', 'alphanumeric:"Sorry! colon(:) Pipe(|)  comma(,) semicolon(;) greator then(>) lesser then(<)  are not allowed"', 'alphanumeric:"¡Lo siento! dos puntos (:) vertical (|) coma (,) punto y coma (;) mayor que (>) menor que (<) no están permitidos"', 'fa-user', 0, NOW());

INSERT INTO rpts_fields (name_en, name_es, description_en, description_es, html_name, html_type, html_list_values_en, html_list_values_es, html_validations, html_validations_messages_en, html_validations_messages_es, icon, recom, created_on)  
VALUES('Extras', 'Extras', 'This control is used to provide the extra info', 'Este control se utiliza para introducir la información adicional', 'rpt_extra', 'Text Box', '', '', 'alphanumeric:true', 'alphanumeric:"Sorry! colon(:) Pipe(|)  comma(,) semicolon(;) greator then(>) lesser then(<)  are not allowed"', 'alphanumeric:"¡Lo siento! dos puntos (:) vertical (|) coma (,) punto y coma (;) mayor que (>) menor que (<) no están permitidos"', 'fa-caret-right', 0, NOW());

INSERT INTO rpts_fields (name_en, name_es, description_en, description_es, html_name, html_type, html_list_values_en, html_list_values_es, html_validations, html_validations_messages_en, html_validations_messages_es, icon, recom, created_on)  
VALUES('Duration', 'Duración', 'This control is used to provide the duration', 'Este control se utiliza para introducir la duración', 'rpt_dura', 'Text Box', '', '', 'alphanumeric:true', 'alphanumeric:"Sorry! colon(:) Pipe(|)  comma(,) semicolon(;) greator then(>) lesser then(<)  are not allowed"', 'alphanumeric:"¡Lo siento! dos puntos (:) vertical (|) coma (,) punto y coma (;) mayor que (>) menor que (<) no están permitidos"', 'fa-caret-right', 0, NOW());

INSERT INTO rpts_fields (name_en, name_es, description_en, description_es, html_name, html_type, html_list_values_en, html_list_values_es, html_validations, html_validations_messages_en, html_validations_messages_es, icon, recom, created_on)  
VALUES('Euros', 'Euros', 'This control is used to provide the total euros', 'Este control se utiliza para introducir los totales de euros', 'rpt_euros', 'Text Box', '', '', 'alphanumeric:true', 'alphanumeric:"Sorry! colon(:) Pipe(|)  comma(,) semicolon(;) greator then(>) lesser then(<)  are not allowed"', 'alphanumeric:"¡Lo siento! dos puntos (:) vertical (|) coma (,) punto y coma (;) mayor que (>) menor que (<) no están permitidos"', 'fa-eur', 0, NOW());

INSERT INTO rpts_fields (name_en, name_es, description_en, description_es, html_name, html_type, html_list_values_en, html_list_values_es, html_validations, html_validations_messages_en, html_validations_messages_es, icon, recom, created_on)  
VALUES('Description', 'Descripción', 'This control is used to provide the details of the service provided', 'Este control se utiliza para introducir los detalles del servicio prestado', 'rpt_desc', 'Textarea', '', '', 'alphanumeric:true', 'alphanumeric:"Sorry! colon(:) Pipe(|)  comma(,) semicolon(;) greator then(>) lesser then(<)  are not allowed"', 'alphanumeric:"¡Lo siento! dos puntos (:) vertical (|) coma (,) punto y coma (;) mayor que (>) menor que (<) no están permitidos"', '', 0, NOW());

INSERT INTO rpts_fields (name_en, name_es, description_en, description_es, html_name, html_type, html_list_values_en, html_list_values_es, html_validations, html_validations_messages_en, html_validations_messages_es, icon, recom, created_on)  
VALUES('Data Report', 'Datos de informe', 'This control is used to provide the details of data report', 'Este control se utiliza para introducir los detalles del informe de datos', 'rpt_dtrpt', 'Textarea', '', '', 'alphanumeric:true', 'alphanumeric:"Sorry! colon(:) Pipe(|)  comma(,) semicolon(;) greator then(>) lesser then(<)  are not allowed"', 'alphanumeric:"¡Lo siento! dos puntos (:) vertical (|) coma (,) punto y coma (;) mayor que (>) menor que (<) no están permitidos"', '', 0, NOW());

INSERT INTO rpts_fields (name_en, name_es, description_en, description_es, html_name, html_type, html_list_values_en, html_list_values_es, html_validations, html_validations_messages_en, html_validations_messages_es, icon, recom, created_on)  
VALUES('Observations', 'Observaciones', 'This control is used to provide the details on the observations noticed in the service', 'Este control se utiliza para introducir los detalles sobre las observaciones señaladas en el servicio', 'rpt_obs', 'Textarea', '', '', 'alphanumeric:true', 'alphanumeric:"Sorry! colon(:) Pipe(|)  comma(,) semicolon(;) greator then(>) lesser then(<)  are not allowed"', 'alphanumeric:"¡Lo siento! dos puntos (:) vertical (|) coma (,) punto y coma (;) mayor que (>) menor que (<) no están permitidos"', '', 0, NOW());

INSERT INTO rpts_fields (name_en, name_es, description_en, description_es, html_name, html_type, html_list_values_en, html_list_values_es, html_validations, html_validations_messages_en, html_validations_messages_es, icon, recom, created_on)  
VALUES('Incidences', 'Incidencias', 'This control is used to provide the details on the incidences noticed in the service', 'Este control se utiliza para introducir los detalles sobre las incidencias notado en el servicio', 'rpt_incd', 'Textarea', '', '', 'alphanumeric:true', 'alphanumeric:"Sorry! colon(:) Pipe(|)  comma(,) semicolon(;) greator then(>) lesser then(<)  are not allowed"', 'alphanumeric:"¡Lo siento! dos puntos (:) vertical (|) coma (,) punto y coma (;) mayor que (>) menor que (<) no están permitidos"', '', 0, NOW());

INSERT INTO rpts_fields (name_en, name_es, description_en, description_es, html_name, html_type, html_list_values_en, html_list_values_es, html_validations, html_validations_messages_en, html_validations_messages_es, icon, recom, created_on)  
VALUES('Signature', 'Firma', 'This control provides the sign pad for signature', 'Este control introduce la plataforma de señal para la firma', 'rpt_signpad', 'Signpad', '', '', '', '', '', '', 1, NOW());

INSERT INTO rpts_fields (name_en, name_es, description_en, description_es, html_name, html_type, html_list_values_en, html_list_values_es, html_validations, html_validations_messages_en, html_validations_messages_es, icon, recom, created_on)  
VALUES('Photo', 'Foto', 'This control allows to upload the photo from the service site', 'Este control permite al subir la foto desde el sitio de servicio', 'rpt_photo', 'Image', '', '', '', '', '', '', 0, NOW());

INSERT INTO rpts_fields (name_en, name_es, description_en, description_es, html_name, html_type, html_list_values_en, html_list_values_es, html_validations, html_validations_messages_en, html_validations_messages_es, icon, recom, created_on)  
VALUES('Tasks List (Task - Value)', 'Lista de Tareas (Tarea - Valor)', 'This control provides the strucuted table to list the tasks attempted in the service', 'Este control introduce la tabla estructurado para enumerar las tareas intentadas en el servicio', 'rpt_tsklist', 'Dyna Text Box|2', '', '', 'alphanumeric:true', 'alphanumeric:"Sorry! colon(:) Pipe(|)  comma(,) semicolon(;) greator then(>) lesser then(<)  are not allowed"', 'alphanumeric:"¡Lo siento! dos puntos (:) vertical (|) coma (,) punto y coma (;) mayor que (>) menor que (<) no están permitidos"', '', 0, NOW());

INSERT INTO rpts_fields (name_en, name_es, description_en, description_es, html_name, html_type, html_list_values_en, html_list_values_es, html_validations, html_validations_messages_en, html_validations_messages_es, icon, recom, created_on)  
VALUES('Measurements List (Measure - Value)', 'Lista Mediciones (Medida - Valor)', 'This control provides the strucuted table to list the measurements details used in the service', 'Este control introduce la tabla estructurado para incluir los datos de las mediciones utilizadas en el servicio', 'rpt_measurelist', 'Dyna Text Box|2', '', '', 'alphanumeric:true', 'alphanumeric:"Sorry! colon(:) Pipe(|)  comma(,) semicolon(;) greator then(>) lesser then(<)  are not allowed"', 'alphanumeric:"¡Lo siento! dos puntos (:) vertical (|) coma (,) punto y coma (;) mayor que (>) menor que (<) no están permitidos"', '', 0, NOW());

INSERT INTO rpts_fields (name_en, name_es, description_en, description_es, html_name, html_type, html_list_values_en, html_list_values_es, html_validations, html_validations_messages_en, html_validations_messages_es, icon, recom, created_on)  
VALUES('Products Used (Product - Qty)', 'Productos Utilizados (Producto - Cantidad)', 'This control provides the strucuted table to list the products used in the service along with the quantity', 'Este control introduce la tabla estructurada a la lista de los productos que se utilizan en el servicio junto con la cantidad', 'rpt_prdqtylist', 'Dyna Text Box|2', '', '', 'alphanumeric:true', 'alphanumeric:"Sorry! colon(:) Pipe(|)  comma(,) semicolon(;) greator then(>) lesser then(<)  are not allowed"', 'alphanumeric:"¡Lo siento! dos puntos (:) vertical (|) coma (,) punto y coma (;) mayor que (>) menor que (<) no están permitidos"', '', 0, NOW());

INSERT INTO rpts_fields (name_en, name_es, description_en, description_es, html_name, html_type, html_list_values_en, html_list_values_es, html_validations, html_validations_messages_en, html_validations_messages_es, icon, recom, created_on)  
VALUES('​​Filling Time', 'Tie​mpo​ de ​Llenado', 'This control is related to the Pool Maintenance task to provide filling time value', 'Este control está relacionado con el Mantenimiento de Piscinas y y registra el tiempo de llenado', 'rpt_fltime', 'Combo Box', '5 mins|10 mins|15 mins|20 mins', '5 mins|10 mins|15 mins|20 mins', '', '', '', '', 0, NOW());

INSERT INTO rpts_fields (name_en, name_es, description_en, description_es, html_name, html_type, html_list_values_en, html_list_values_es, html_validations, html_validations_messages_en, html_validations_messages_es, icon, recom, created_on)  
VALUES('​​Cleaning Leaves​​', 'Recoger Hojas', 'This control is related to the Pool Maintenance task to verify if the leaves are cleaned', 'Este control está relacionado con el Mantenimiento de piscinas y es para verificar que se han recogido las hojas', 'rpt_clleaves', 'Checkbox', '', '', '', '', '', 'fa-leaf', 0, NOW());


/**
 * Report Query Filters SQLs
 */
INSERT INTO rpts_qfilters (qfilter_id, qfilter_name_en, qfilter_name_es, qfilter_desc_en, qfilter_desc_es, pfile, created_on)  
VALUES('qFilter1', 'Filter Reports By Time, Client, Worker', 'Informes de filtro por el Tiempo, Cliente, Trabajador', 'Report details based on the period of time, clients and workers', 'Detalles del informe basado en el período de tiempo, los clientes y los trabajadores', 'rptQueryTCW.php', NOW());

INSERT INTO rpts_qfilters (qfilter_id, qfilter_name_en, qfilter_name_es, qfilter_desc_en, qfilter_desc_es, pfile, created_on)  
VALUES('qFilter2', 'Calculate Worker Hours', 'Calcular Trabajador Horas', 'Calculate total no. of hours spend by the worker for the client', 'Calcular total no. de horas pasar por el trabajador para el cliente', 'rptQueryCalWHrs.php', NOW());

INSERT INTO rpts_qfilters (qfilter_id, qfilter_name_en, qfilter_name_es, qfilter_desc_en, qfilter_desc_es, pfile, created_on)  
VALUES('qFilter3', 'Product Quantity based on client', 'Cantidad de Productos basados en el Cliente', 'Product Quantity used by the worker on a client', 'Cantidad de producto utilizado por el trabajador en un cliente', 'rptQueryTtlPrdQty.php', NOW());