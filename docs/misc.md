## Misc

#### Updates

If you are using the PHAR release, you can easily update your version by running the self-update command:
```bash
./env-checker.phar self-update
```

#### Concepts

Throughout code I refer to a 'specification' and an 'implementation' side.

With **specification** I mean the `.env` or `.env.dist` file in your project; 
it **specifies** what environment variables your application uses.

With **implementation** I refer to the various files that your deployment pipeline uses
to run your servers; they **implement** the environment variables specified by your application.


#### Why

Deployment can fail because of missing environment variables. 
This is especially true for projects that have their deployment
configuration located in a separate repository, making it hard to keep things in sync.


#### Roadmap

- Make the comparison smarter:
    - Collect meta-data while loading to inform users *where* certain variables are specified, 
      and/or where they should be implemented. Probably some kind of strategy pattern.
    - Add a 'strict' mode that checks the other way round: vars that are implemented but not specified.
- Add more (implementation) loaders; adding support for tools like Ansible etc.  
- Make the general loading smarter (e.g. notice differences between 'micro' services)
- Add public key verification to the PHAR distribution.
