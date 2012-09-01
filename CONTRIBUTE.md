# How to contribute

## Setup

 1. Setup a GitHub account (http://github.com/), if you haven't yet
 2. Fork the Clansuite respository (http://github.com/Clansuite/Clansuite)
 3. Clone your fork locally and enter it (use your own GitHub username
    in the statement below)

    ```sh
    % git clone http://github.com/<username>/Clansuite.git
    % cd Clansuite
    ```

 4. Add a remote to the canonical Clansuite repository, so you can keep your fork
    up-to-date:

    ```sh
    % git remote add upstream http://github.com/Clansuite/Clansuite.git
    ```

 5. Fetch and merge the latest remote changes in your local branch

    ```sh
    % git pull upstream develop
    ```

## Working on Clansuite

When working on Clansuite, we recommend you do each new feature or
bugfix in a new branch. This simplifies the task of code review as well
as of merging your changes into the canonical repository.

A typical work flow will then consist of the following:

 1. Create a new local branch based off your develop branch.
 2. Switch to your new local branch.

    (This step can be combined with the previous step with the use of)

    ```sh
    % git checkout -b <branchname>
    ```

 3. Do some work, commit, repeat as necessary.
 4. Push the local branch to your remote repository.

    ```sh
    % git push origin <branchname>:<branchname>
    ```

 5. Send a pull request.

## Keeping Up-to-Date

Periodically, you should update your fork or personal repository to
match the canonical Clansuite repository. In the above setup, we have
added a remote to the Clansuite repository, which allows you to do
the following

    ```sh
    % git checkout develop
    % git pull upstream develop
    % git push origin
    ```