# sample app using symfony and message broker (rabbitmq)
# goal
# create a post request in users service /users
# post request contains firstName,lastName,email in the body
# request data will be inserted in the users table
# request data will be sent in the message broker (rabbitmq)
# queued message will be consume by the notifications service
# consumed message will be written in log file (file_from_users${unixtimestamp}.log)

#how to run the app
#open terminal navigate to project directory
