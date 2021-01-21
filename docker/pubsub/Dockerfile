FROM google/cloud-sdk:321.0.0

RUN mkdir /var/pubsub

VOLUME /var/pubsub

EXPOSE 8085

ENV PUBSUB_EMULATOR_HOST=0.0.0.0:8085

CMD [ "gcloud", "beta", "emulators", "pubsub", "start", "--data-dir=/var/pubsub", "--host-port=0.0.0.0:8085", "--log-http", "--verbosity=debug", "--user-output-enabled", "--project=devel" ]
