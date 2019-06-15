## env-checker

Small lint script that checks if your k8s deployment contains all 
environment variables that your project requires.


## Requirements

- PHP >= 7.2

## Installation



## Usage

#### Locally (e.g. a pre-commit hook)

```
# assuming you have cloned this repository...
chmod +x env-checker/bin/env-checker

# now to run the test...
./env-checker/bin/env-checker ./your-project/.env.dist ./your-k8s-deployment
```

#### CI (e.g. add the end of your Travis scripts)

```
# assuming you are now in your project's directory...
cd ../
git clone git@github.com:pararius/env-checker
git clone git@github.com:your_vendor/your_k8s_deployment
chmod +x env-checker/bin/env-checker

# now to run the test...
./env-checker/bin/env-checker ./your-project/.env.dist ./your-k8s-deployment
```
