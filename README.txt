
THIS PROJECT IS BASED ON https://buildasaasappwithflask.com/ , AN EXCELLENT COURSE TO BUILDING PYTHON FLASK APPLICATIONS BY NICK JANETAKIS

This was my first Flask project, and I made good decision to base it on the course to get all the necessary docker stuff etc. as quickly 
working as possible. Nick provides excellent support for his courses, so if you feel need to learn how to build this further or any other 
Flask application, I sincerely recommend his course.


INSTALLING DOCKER

Ubuntu:
https://docs.docker.com/engine/installation/linux/ubuntu/

Debian
https://docs.docker.com/engine/installation/linux/debian/

RHEL
https://docs.docker.com/engine/installation/linux/rhel/

CentOS
https://docs.docker.com/engine/installation/linux/centos/

Fedora
https://docs.docker.com/engine/installation/linux/fedora/

Further information for other platforms:
https://nickjanetakis.com/blog/should-you-use-the-docker-toolbox-or-docker-for-mac-windows

CHECK THAT DOCKER IS WORKING

type:
$ docker --version


DOCKER COMPOSE INSTALLATION

Linux users need to download Docker Compose:

curl -L \
https://github.com/docker/compose/releases/download/1.16.1/docker-compose-Linux-x86_64 > \
/tmp/docker-compose && \
chmod +x /tmp/docker-compose && \
sudo mv /tmp/docker-compose /usr/local/bin


BUILDING AND STARTING

type at project home directory:
$ docker-compose up --build

After this you can visit the site in browser at address:
http://localhost:8000/




