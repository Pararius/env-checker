# env-checker

Small tool that checks if your k8s deployment contains all 
environment variables that your project requires.


## Requirements

- Machine with PHP installed (>= 7.2)
- You deploy your project on a Kubernetes cluster, 
  but maintain it's configuration in a separate repository.


## Installation

#### PHAR

This tool is automatically compiled into a PHAR on every release.
Go to our [releases](https://github.com/Pararius/env-checker/releases) page for the latest version.

```bash
wget https://github.com/pararius/env-checker/releases/download/VERSION/env-checker.phar
```

**NOTE:** Replace `VERSION` with the version you would like to install.


#### Composer

```bash
composer require --dev pararius/env-checker
```


## Updates

If you are using the PHAR release, you can easily update your version by running the self-update command:
```bash

```

## Usage

This assumes you have installed env-checker using the PHAR method, mentioned [above](#PHAR). 

#### Locally (e.g. a pre-commit hook)

```bash
./env-checker.phar ./your-project/.env.dist ./path-to/your-k8s-deployment-files
```

#### CI (e.g. add the end of your Travis scripts)

```bash
# clone your (separate ^_^) deployment repository
git clone git@github.com:your-company/your-companys-k8s-repo

# now run the test...
./env-checker.phar ./your-project/.env.dist ./your-k8s-deployment
```

You can use the quiet option (`-q`) to silence any output if you wish to do so.


## Concepts

Throughout code I refer to a 'specification' and an 'implementation' side.

With **specification** I mean the `.env` or `.env.dist` file in your project; 
it **specifies** what environment variables your application uses.

With **implementation** I refer to the various files that your deployment pipeline uses
to run your servers; they **implement** the environment variables specified by your application.


## Why

Deployment can fail because of missing environment variables. 
This is especially true for projects that have their deployment
configuration located in a separate repository, making it hard to keep things in sync.


## Roadmap

- Make the comparison smarter:
    - Collect meta-data while loading to inform users *where* certain variables are specified, 
      and/or where they should be implemented. Probably some kind of strategy pattern.
    - Add a 'strict' mode that checks the other way round: vars that are implemented but not specified.
- Add more (implementation) loaders; adding support for tools like Ansible etc.  
- Make the general loading smarter (e.g. notice differences between 'micro' services)
- Add public key verification to the PHAR distribution.
 