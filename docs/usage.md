## Usage

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
