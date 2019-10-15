<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
   return 'Web Wervice Realizado con LSCodeGenerator';
});

$router->group(['middleware' => []], function () use ($router) {
   $router->post('/login', ['uses' => 'AuthController@login']);
   $router->post('/register', ['uses' => 'AuthController@register']);
   $router->post('/password_recovery_request', ['uses' => 'AuthController@passwordRecoveryRequest']);
   $router->get('/password_recovery', ['uses' => 'AuthController@passwordRecovery']);

   $router->get('/institution', ['uses' => 'InstitutionController@get']);
   $router->get('/institutionlogo', ['uses' => 'InstitutionLogoController@get']);
   $router->get('/author', ['uses' => 'AuthorController@get']);
   $router->get('/publicationtype', ['uses' => 'PublicationTypeController@get']);
   $router->get('/publicationcomment', ['uses' => 'PublicationCommentController@get']);
   $router->get('/publication', ['uses' => 'PublicationController@get']);
   $router->get('/publicationattachment', ['uses' => 'PublicationAttachmentController@get']);
   $router->post('get_publication_attachment_by_publication_id', ['uses' => 'DocumentSharingController@get_publication_attachment_by_publication_id']);
   $router->post('get_publication_comments', ['uses' => 'DocumentSharingController@get_publication_comments']);
   $router->post('publications_filtered', ['uses' => 'DocumentSharingController@publications_filtered']);
   $router->get('/institutioninternaldivition', ['uses' => 'InstitutionInternalDivitionController@get']);
   
});

$router->group(['middleware' => ['auth']], function () use ($router) {
   $router->post('/user/password_change', ['uses' => 'AuthController@passwordChange']);
   
   //NEGOCIO
   $router->get('person_user', ['uses' => 'DocumentSharingController@person_user']);
   $router->get('my_institution', ['uses' => 'DocumentSharingController@my_institution']);
   $router->get('get_institution_publishers', ['uses' => 'DocumentSharingController@get_institution_publishers']);
   $router->post('get_institution_publications', ['uses' => 'DocumentSharingController@get_institution_publications']);
   
   //CRUD ProfilePicture
   $router->post('/profilepicture', ['uses' => 'ProfilePictureController@post']);
   $router->get('/profilepicture', ['uses' => 'ProfilePictureController@get']);
   $router->get('/profilepicture/paginate', ['uses' => 'ProfilePictureController@paginate']);
   $router->put('/profilepicture', ['uses' => 'ProfilePictureController@put']);
   $router->delete('/profilepicture', ['uses' => 'ProfilePictureController@delete']);

   //CRUD User
   $router->post('/user', ['uses' => 'UserController@post']);
   $router->get('/user', ['uses' => 'UserController@get']);
   $router->get('/user/paginate', ['uses' => 'UserController@paginate']);
   $router->put('/user', ['uses' => 'UserController@put']);
   $router->delete('/user', ['uses' => 'UserController@delete']);

   //CRUD Institution
   $router->post('/institution', ['uses' => 'InstitutionController@post']);
   $router->get('/institution/paginate', ['uses' => 'InstitutionController@paginate']);
   $router->get('/institution/backup', ['uses' => 'InstitutionController@backup']);
   $router->put('/institution', ['uses' => 'InstitutionController@put']);
   $router->delete('/institution', ['uses' => 'InstitutionController@delete']);
   $router->post('/institution/masive_load', ['uses' => 'InstitutionController@masiveLoad']);

   //CRUD Person
   $router->post('/person', ['uses' => 'PersonController@post']);
   $router->get('/person', ['uses' => 'PersonController@get']);
   $router->get('/person/paginate', ['uses' => 'PersonController@paginate']);
   $router->get('/person/backup', ['uses' => 'PersonController@backup']);
   $router->put('/person', ['uses' => 'PersonController@put']);
   $router->delete('/person', ['uses' => 'PersonController@delete']);
   $router->post('/person/masive_load', ['uses' => 'PersonController@masiveLoad']);

   //CRUD InstitutionLogo
   $router->post('/institutionlogo', ['uses' => 'InstitutionLogoController@post']);
   $router->get('/institutionlogo/paginate', ['uses' => 'InstitutionLogoController@paginate']);
   $router->get('/institutionlogo/backup', ['uses' => 'InstitutionLogoController@backup']);
   $router->put('/institutionlogo', ['uses' => 'InstitutionLogoController@put']);
   $router->delete('/institutionlogo', ['uses' => 'InstitutionLogoController@delete']);
   $router->post('/institutionlogo/masive_load', ['uses' => 'InstitutionLogoController@masiveLoad']);

   //CRUD Author
   $router->post('/author', ['uses' => 'AuthorController@post']);
   $router->get('/author/paginate', ['uses' => 'AuthorController@paginate']);
   $router->get('/author/backup', ['uses' => 'AuthorController@backup']);
   $router->put('/author', ['uses' => 'AuthorController@put']);
   $router->delete('/author', ['uses' => 'AuthorController@delete']);
   $router->post('/author/masive_load', ['uses' => 'AuthorController@masiveLoad']);

   //CRUD PublicationComment
   $router->post('/publicationcomment', ['uses' => 'PublicationCommentController@post']);
   $router->get('/publicationcomment/paginate', ['uses' => 'PublicationCommentController@paginate']);
   $router->get('/publicationcomment/backup', ['uses' => 'PublicationCommentController@backup']);
   $router->put('/publicationcomment', ['uses' => 'PublicationCommentController@put']);
   $router->delete('/publicationcomment', ['uses' => 'PublicationCommentController@delete']);
   $router->post('/publicationcomment/masive_load', ['uses' => 'PublicationCommentController@masiveLoad']);

   //CRUD PublicationType
   $router->post('/publicationtype', ['uses' => 'PublicationTypeController@post']);
   $router->get('/publicationtype/paginate', ['uses' => 'PublicationTypeController@paginate']);
   $router->get('/publicationtype/backup', ['uses' => 'PublicationTypeController@backup']);
   $router->put('/publicationtype', ['uses' => 'PublicationTypeController@put']);
   $router->delete('/publicationtype', ['uses' => 'PublicationTypeController@delete']);
   $router->post('/publicationtype/masive_load', ['uses' => 'PublicationTypeController@masiveLoad']);

   //CRUD InstitutionInternalDivition
   $router->post('/institutioninternaldivition', ['uses' => 'InstitutionInternalDivitionController@post']);
   $router->get('/institutioninternaldivition/paginate', ['uses' => 'InstitutionInternalDivitionController@paginate']);
   $router->get('/institutioninternaldivition/backup', ['uses' => 'InstitutionInternalDivitionController@backup']);
   $router->put('/institutioninternaldivition', ['uses' => 'InstitutionInternalDivitionController@put']);
   $router->delete('/institutioninternaldivition', ['uses' => 'InstitutionInternalDivitionController@delete']);
   $router->post('/institutioninternaldivition/masive_load', ['uses' => 'InstitutionInternalDivitionController@masiveLoad']);

   //CRUD InstitutionInternalRol
   $router->post('/institutioninternalrol', ['uses' => 'InstitutionInternalRolController@post']);
   $router->get('/institutioninternalrol', ['uses' => 'InstitutionInternalRolController@get']);
   $router->get('/institutioninternalrol/paginate', ['uses' => 'InstitutionInternalRolController@paginate']);
   $router->get('/institutioninternalrol/backup', ['uses' => 'InstitutionInternalRolController@backup']);
   $router->put('/institutioninternalrol', ['uses' => 'InstitutionInternalRolController@put']);
   $router->delete('/institutioninternalrol', ['uses' => 'InstitutionInternalRolController@delete']);
   $router->post('/institutioninternalrol/masive_load', ['uses' => 'InstitutionInternalRolController@masiveLoad']);

   //CRUD InstitutionInternalRolAssignment
   $router->post('/institutioninternalrolassignment', ['uses' => 'InstitutionInternalRolAssignmentController@post']);
   $router->get('/institutioninternalrolassignment', ['uses' => 'InstitutionInternalRolAssignmentController@get']);
   $router->get('/institutioninternalrolassignment/paginate', ['uses' => 'InstitutionInternalRolAssignmentController@paginate']);
   $router->get('/institutioninternalrolassignment/backup', ['uses' => 'InstitutionInternalRolAssignmentController@backup']);
   $router->put('/institutioninternalrolassignment', ['uses' => 'InstitutionInternalRolAssignmentController@put']);
   $router->delete('/institutioninternalrolassignment', ['uses' => 'InstitutionInternalRolAssignmentController@delete']);
   $router->post('/institutioninternalrolassignment/masive_load', ['uses' => 'InstitutionInternalRolAssignmentController@masiveLoad']);

   //CRUD AccountRol
   $router->post('/accountrol', ['uses' => 'AccountRolController@post']);
   $router->get('/accountrol', ['uses' => 'AccountRolController@get']);
   $router->get('/accountrol/paginate', ['uses' => 'AccountRolController@paginate']);
   $router->get('/accountrol/backup', ['uses' => 'AccountRolController@backup']);
   $router->put('/accountrol', ['uses' => 'AccountRolController@put']);
   $router->delete('/accountrol', ['uses' => 'AccountRolController@delete']);
   $router->post('/accountrol/masive_load', ['uses' => 'AccountRolController@masiveLoad']);

   //CRUD AdministrativeRol
   $router->post('/administrativerol', ['uses' => 'AdministrativeRolController@post']);
   $router->get('/administrativerol', ['uses' => 'AdministrativeRolController@get']);
   $router->get('/administrativerol/paginate', ['uses' => 'AdministrativeRolController@paginate']);
   $router->get('/administrativerol/backup', ['uses' => 'AdministrativeRolController@backup']);
   $router->put('/administrativerol', ['uses' => 'AdministrativeRolController@put']);
   $router->delete('/administrativerol', ['uses' => 'AdministrativeRolController@delete']);
   $router->post('/administrativerol/masive_load', ['uses' => 'AdministrativeRolController@masiveLoad']);

   //CRUD Publication
   $router->post('/publication', ['uses' => 'PublicationController@post']);
   $router->get('/publication/paginate', ['uses' => 'PublicationController@paginate']);
   $router->get('/publication/backup', ['uses' => 'PublicationController@backup']);
   $router->put('/publication', ['uses' => 'PublicationController@put']);
   $router->delete('/publication', ['uses' => 'PublicationController@delete']);
   $router->post('/publication/masive_load', ['uses' => 'PublicationController@masiveLoad']);

   //CRUD PublicationAttachment
   $router->post('/publicationattachment', ['uses' => 'PublicationAttachmentController@post']);
   $router->get('/publicationattachment/paginate', ['uses' => 'PublicationAttachmentController@paginate']);
   $router->get('/publicationattachment/backup', ['uses' => 'PublicationAttachmentController@backup']);
   $router->put('/publicationattachment', ['uses' => 'PublicationAttachmentController@put']);
   $router->delete('/publicationattachment', ['uses' => 'PublicationAttachmentController@delete']);
   $router->post('/publicationattachment/masive_load', ['uses' => 'PublicationAttachmentController@masiveLoad']);
});
