.PHONY:	build-all messages notifications users messages-build notifications-build users-build start-selected

# Target to build all services
build-all:	messages-build	users-build	 notifications-build

# Target to build the message service
messages-build:
	@$(MAKE) -C messages build-messages

# Target to build the notifications service
# supervisord is not executing rabbitmq:consume if this is used
notifications-build:
	@$(MAKE) -C notifications build-notifications

# Target to build the users service
users-build:
	@$(MAKE) -C users build-users

users:
	@$(MAKE) -C users run-users

messages:
	@$(MAKE) -C messages run-messages

notifications:
	@$(MAKE) -C notifications run-notifications

start-selected:	messages notifications users

