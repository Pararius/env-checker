## env-checker

Small tool that checks if your k8s deployment contains all 
environment variables that your project requires.


## Requirements

- PHP >= 7.2


## Installation

```
composer require --dev pararius/env-checker
```


## Usage

Since the CLI is built using Symfony Console, you can run the command 
without arguments for a quick summary of possible commands and arguments/options. 

#### Locally (e.g. a pre-commit hook)

```
chmod +x env-checker/bin/env-checker

# run the checks...
./env-checker/bin/env-checker ./your-project/.env.dist ./your-k8s-deployment
```

#### CI (e.g. add the end of your Travis scripts)

**NOTE:** This assumes you have access to your project's files (replace `./your-project/`)

```
# will need to clone this first if you have not added env-checker as a composer dependency...
git clone git@github.com:pararius/env-checker

git clone git@github.com:your_vendor/your_k8s_deployment
chmod +x env-checker/bin/env-checker

# now to run the test...
./env-checker/bin/env-checker ./your-project/.env.dist ./your-k8s-deployment
```
