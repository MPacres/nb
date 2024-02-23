# sample app using symfony and message broker (rabbitmq)
goal
create a post request in users service /users
post request contains firstName,lastName,email in the body
request data will be inserted in the users table
request data will be sent in the message broker (rabbitmq)
queued message will be consume by the notifications service
consumed message will be written in log file (file_from_users${unixtimestamp}.log)

# How to run the app
open terminal navigate to project root directory
run ls : files inside the root directory should be
        - Makefile
        - messages
        - users
        - notifications
run "make build-all" : this will build all the docker containers then run supervisord.conf
after build is complete run "make start-selected" : this will run all the docker containers in your local machine


# Important
  supervisord.conf might not run in your container (permission issue,etc...)
  supervisord.conf is required for notifications and users service
    - consume worker (notifications service)
    - PHP:migrate (users service)

  1.0 : if supervisord.conf fails in users service
     - open terminal
     - go to root directory
     - cd users
     - run "docker exec -ti users-web-1 bash"
     - run "php bin/console doctrine:migrations:migrate --no-interaction"
     - restart docker container
 
  2.0 : if supervisord.conf fails in notifications service
     - open terminal
     - go to root directory
     - cd notifications
     - run "docker exec -ti notifications-web-1 bash"
     - run "php bin/console app:rabbitmq:consume"
     - restart docker container

 # Testing the functionality
 
 users service : 
   - open postman
   - create a POST request
   - http://localhost:8080/users
   - request body {"email":"sampleemail@zxc.com", "firstName": "thisIsFirstName", "lastName":"thisIsLastName"}
   - send request

  validate if data is saved in the database:
  
    - open mysql workbench
    - connect to localhost:3026 , database: users, username:assessment, password:assessment
        * select users database
        * execute command "select * from users"
        * it should contain the user information that you send by the post request in users service ("http://localhost:8080/users")

  validate if data is sent in the message broker:
  
    - open browser type "http://localhost:15672/"
    - login credentials is in "users/docker-compose.yaml" or username:message, password:message, host:rabbitmq
    - once in the rabbitmq dashboard navigate to queues to see list of message in queue
    - create another post request from "http://localhost:8080/users" to verify if messages are received by the message broker

  validate if message is consume by notifications service:
  
    - open terminal
    - go to root directory
    - cd notifications
    - execute command "docker exec -ti notifications-web-1 bash"
    - ls (here you will see all the consumed messages that is written in the log file)
  
 
    


