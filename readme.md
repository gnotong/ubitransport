# ubitransport test

---

### Prerequisites

- You need `docker` and `docker-compose` installed and running on your computer
- Make sure you have `make` working on your machine
- Istead of `localhost`, you can use your own domain

---

### Launch the application

- clone the following repository

```
git clone https://github.com/gabrielnotong/ubitransport.git
```

- use a shell terminal(like terminator) go to the main directory, where the `Makefile` is located

Launch the command `make install` which will make the application available on this [link](http://localhost:8000/api)

*NB:* to know available commands, type `make` or `make help`

- The default db(ubitransport) is configured in the `.env` file. You can create your own file like `.env.local` using the same `ubitransport` db name

---

### API Usage

- Swagger: available [here](http://localhost:8000/api)

- You can use `postman` or `swagger` to interact with the api

*`UbiTransport.postman_collection.json`*: is the file to be imported into `postman` it is located in the root project directory

```
- it contains postman api requests welformatted
- It can be imported within postman collections
```

- The list of student is a paginated api: [localhost:8000/api/students?page=1](http://localhost:8000/api/students?page=1)

### Tests

- `make all-tests`: to run functional and unit tests
- `make unit-test`: to run unit tests
- `make functional-test`: to run functional tests
