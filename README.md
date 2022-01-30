1.Clone the repo
2.Composer Install && Run Migrations
3.Use Postman/ThunderClient to register then login
http://localhost:8000/api/login (*POST Request)
4.Get The bearer token for authorization from the login response
5.Use the routes api to get the urls for testing(* GET requests);
e.g. http://localhost:8000/api/units_balance
    http://localhost:8000/api/send_sms

N/B Sending the requests without authentication will result in unauthenticated error
laravel sanctum is used to protect api routes.
Cheers
