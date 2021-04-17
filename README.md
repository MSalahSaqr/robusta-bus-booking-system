
## About

This task is a part of interview proccess of Rubusta. I was requird to create 2 API endpoints one to get available bus seats and the other one is to reserve seats along with authorization and authentication. I also documented the API endpoints using Swagger.

## How to Run

- Pull the repository
- Download the DB dumb from this [link](https://drive.google.com/file/d/14Iut0lOdE475NoWNNsmgejMZXSkpK69c/view?usp=sharing)
- Restore the dumb to MySQL server
- Set your DB credintials and other required settings in the env file (address,port,username,password)
- run the command *composer install* to install all dependacies
- run the command *php artisan passport:keys* to generate keys
- run the command *php artisan key:generate* to set keys
- Use artisan to serve your app (you can also host the app on any server of your choice)

## The Data in the Database 

- The Database contains 2 Trips with ids [1,2]
- Every trip has a route which has multiple stations
- Every trip has a bus which has multiple seats
- for testing the trip with id = 1 has a bus with 12 seats with ids[1...12] and route with 4 stations {id:1, name:cairo} {id=2, name:Mansoura} {id=3, name:Banha} {id=4, name:Tanta} which are ordered {id:1, order:1} {id:3, order:2} {id:2, order:3} {id:4, order:4}
- Some seates are reserved for trip id = 1
- Trip id = 2's bus has no seats yet.
- swagger link is /api/docs

### Remarks on the design

- The design choices made are all based on the assumption that this is a slice of a bigger App
- The implemented features are implemented with extendability in mind
- Layer of the system are built with decoupling in mind