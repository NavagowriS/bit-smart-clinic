# Smart Clinic

### How to setup

1. Once cloned the repo, make sure the public folder inside the project is pointed as serving folder for the http server.
2. Run the SQL file in the project to set up the user table with admin user for login/authentication.
3. bootstrap.php file contains the connection details for the database server. Update the username, password and database name if needed.
4. run `npm install` to set up Vue building workflow. The project comes with Laravel-mix for building Vue SPA. After running `npm install` you can use `npx mix` to build Vue code, or use `npx mix watch` to run watch process that watch for any source code modification and build on the fly. more details [here.](https://laravel-mix.com/docs/6.0/upgrade#update-your-npm-scripts)



### Login details

Once you run the SQL file, you will have a single user (Administrator) in the database.

You can log-in to the app using the following credentials.

`username: admin`

`password: admin`

## Database Structure

```

users
- id
- username
- password_hash
- email
- full_name
- role [ADMIN, STAFF, USER]


users_auth_keys
- user_id
- auth_key
- valid_till


doctors
- id
- name
- email
- phone
- dob
- speciality_id (fk = doctor_speciality->id)


doctor_speciality
- id
- speciality


patients
- id
- full_name
- dob
- age
- phone
- nic
- address
- guardian_name
- guardian_phone
- gender [MALE, FEMALE, OTHER]
- login_name
- login_pass


clinics
- id
- title
- doctor_in_charge_id (fk = doctors->id)


clinic_patients
- id
- clinic_id (fk = clincis->id)
- patient_id (fk = patients->id)
- since


clinic_visits
- id
- clinic_patient_id (fk = clinic_patients->id)
- visit_date
- token_number
- remarks
- doctor_remarks
- status [ACTIVE, COMPLETED, CANCELLED, MISSED]



drug_categories
- id
- category_name



drugs
- id
- generic_name
- drug_category_id
- quantity_threshold



drug_invoice
- id
- invoice_date
- drug_id (fk = drugs->id)
- quantity



prescriptions
- id
- visit_id (fk = clinic_visits->id)
- status [PENDING, FULFILLED, CANCELLED]



prescription_drugs
- id
- prescription_id (fk = prescriptions->id)
- drug_id
- dose
- frequency
- duration
- remarks




```
