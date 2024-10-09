# Setting up the project
After you cloned the project, take the following step:
1. run from CMD the `composer install` command to get the required dependencies.
2. start the project using `docker compose up --build` command
3. To check that the project is up and running access from a browser the following URL `http://localhost:8080/`.
If everything is ok you should see the following message 'Hello, Slim Framework on Docker!'.

# Accessing the discount functionality
URL: http://localhost:8080/discounts/calculate
Request Method: POST
Request Header parameters:
    - Content-Type: application/json
Query String parameter:
    - order : and as value a valid json string

***Recommendation:*** For the post request use Postman or any alike tool.

# Made decision's
## Docker
Added 3 containers:
- cli: used for running the commands from CLI. From here I executed the test command `vendor/bin/phpunit`
- app: serves as the container which serves the access from browser, Postman etc
- webserver: the scope was to have nginx and virtual hosts
The `.docker` folder contains the required Dockerfile and configuration files for containers

## Code
Used Slim Framework to benefit of route feature, and some objects like Request/Response for controllers. 
The routes are defined in `public\index.php` and as improvement  

The (micro)service logic is found under `src` folder.
Tried to use SOLID as much as possible so that the functionality to be easy to extend the existing functionality based 
on business needs.

### Models 
The scope of the classes from this folder serve as DTO's to be able the pass through the flow the received information 
through request. Also, this classes contain business regarding on how to calculate the total of an order, or an order 
item based on the applied discount(s).

### Transformers
The scope of the classes from this folder is to:
- translate the received information through request to the DTO's
- translate an DTO to the required format for the answer (fields, values format)
The transformers can act like an anticoruption layer, in case the contract of request or response will change.
***Note:*** The response will be formatted under the same format how the data was receives.
  
### Factories
#### OrderTransformerFactory
This factory requires the value of the field `Content-Type` from request header. Based on this field the (micro)service
will know of how the received information is formatted. Current implementation supports only json string.
In case in future the application will require another format (e.g. XML), it will be required to implement an XML 
transformer and extend the factory to be able to initialize the new transformer.
#### DiscountRulesFactory
This factory need to know how to read the required information from DB and how to initialize each type of discount rule. 

### Entities & Repositories
It was decided that the files from folder `data` to mimic the records stored in a DB. This decision was made because for
using a DB it would have been required to write also migrations to create the tables and insert the data. The seeders are
not taken in consideration as the information from `data` folder must be inserted only once.
Starting from the assumption that the (micro)service can communicate with the DB, the following approach was taken:
- Entities will serve as immutable ObjectValues for DB records.
- Repositories mimic the layer which retrieve's the information from DB, even though the required information is 
  identified from loaded data from files.
A new file named discounts-rules.json was added to folder `data` to mimic the structure of how discount rule are being
stored into a DB table
  
### Discount rules
The scope of the classes from this folder is to implemented the criteria for the discounts, and the logic to identify 
when a discount should be applied and when not. Also, this classes need to know that the discount which they represent
need's to be applied on order or order item level.

### Services
#### DiscountApplierService
This service functionality is to parse through the discount rules, and to know in which order the discount rules need 
to be applied.

## Awareness of improvements
On `DiscountsController` inject the instances for DiscountsApplierService, repositories and discount rules through the 
service container (DI container), as the controller should not be aware of how they are initialized.
The only scope of the controller should be to know how to process the request.

For tests reduce code duplication by moving some instantiations or setting some values in on of the following methods 
`setUp()` or `setUpBeforeClass()` based on usage scope. 