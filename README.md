#FH Salzburg Task Pilz Michael

###Run task:
execute setup.php <br />
execute duplicator.php <br />
execute authenticator.php <br />
execute decryptor.php <br />
execute consumer.php<br />

now all jobs are running and you can publish messages.

execute publisher.php

The current setup allows to spawn as much duplicator, authenticator and decryptor as needed.
If you want to use message acknowledgment you should adjust the config.json file before spawing more workers.


###Functionality filter/pipe task:

A Publisher sends message to a Consumer. Before the consumer receives the message, there are 3 workers in between. A Duplicator, Authenticator and a Duplicator.
Each of this worker is receiving the message and sends it to the next worker.

For each step of the filter/pipe pattern i have defined a single exchange and queue.


Publisher sends to DecrypterExchange
Decryptor listen to DecryptoerQueue
Decryptor sends to AuthenticatorExchange
Authenticator listens to AuthenticatorQueue
Authenticator sends to DuplicatorExchange
Duplicator listens to DuplicatorQueue
Duplicator sends to ConsumerExchange
Consumer listen to COnsumerQueue


####Questions 

#####Whicht exchange type is used and why
For this task i defined 4 exchange, each exchange has only one queue.
I used for all of my exchange the fanout type. Because i want that the exchange sends messages to every queue it knows about.
For my configuration this is a advantage, because i make the scaleability very easy. Spawning more instances of a worker would not be a problem. 

#####using message acknowledgment;
In my setup every filter has one instance for the job. So i decided that there is no need for using acknowledgment.
If i would define more workers for the same filter it could make sense to use message acknoledment. 
Because if one of the worker die, the message would be send again to an other worker.


