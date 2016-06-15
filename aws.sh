#!/usr/bin/env bash
# A command line interface for setting up an ec2 instance and associated services.



#export AWS_DEFAULT_PROFILE=default
#export AWS_CONFIG_FILE=config/aws.conf

export AWS_DEFAULT_REGION=us-west-2
export AWS_ACCESS_KEY_ID="GET_FROM_WEB_CONSOLE"
export AWS_SECRET_ACCESS_KEY="GET_FROM_WEB_CONSOLE-USER_MUST_HAVE_PROPER_PERMS"

KEY_NAME="bp-test1"
KEY_FILE="aws_private_key.pem"

SECURITY_GROUP_NAME="BP Test Group"
SECURITY_GROUP_DESCRIPTION="BP test security group, created by command line."



# Confirm that the `aws` command line tool is installed.
if ! command -v 'aws' >/dev/null 2>&1; then
	echo "!! aws command line tools are not installed. Aborting."
	echo "!! See: https://github.com/aws/aws-cli"
	echo "!! See: https://docs.aws.amazon.com/cli/latest/userguide/cli-chap-welcome.html"
	exit 1
fi





# Make sure we have region, key_id and access_key
if [ -z "$AWS_DEFAULT_REGION" ]; then
		echo "!! No default region is configured. Aborting."
		exit 1
fi




# Generate a keypair and local private key file, if they don't already exist.
KEY_FOUND=$?
if [ ! -r "$KEY_FILE" ]; then
	if aws ec2 describe-key-pairs --key-name $KEY_NAME >/dev/null 2>&1; then
		echo "!! Keypair already exists, but no local private key found. Copy the private key to '$KEY_FILE' and retry. Aborting."
		exit 1
	fi

	aws ec2 create-key-pair --key-name $KEY_NAME --query 'KeyMaterial' --output text > "$KEY_FILE"
fi

chmod 400 "$KEY_FILE"


# Verify the signature of the local key file against the API query results.
KEY_FINGERPRINT_FROM_API=$(aws ec2 describe-key-pairs --key-name $KEY_NAME --output=text --query 'KeyPairs[*].KeyFingerprint')
KEY_FINGER_PRINT_FROM_FILE=$(openssl pkcs8 -in $KEY_FILE -inform PEM -outform DER -topk8 -nocrypt | openssl sha1 -c)
KEY_FINGER_PRINT_FROM_FILE=${KEY_FINGER_PRINT_FROM_FILE#(stdin)= }
if [ "$KEY_FINGERPRINT_FROM_API" != "$KEY_FINGER_PRINT_FROM_FILE" ]; then
	echo "!! Fingerprint of local key file '$KEY_FILE' does not match the one returned from the API. Aborting."
	echo "!!  API Fingerprint: $KEY_FINGERPRINT_FROM_API"
	echo "!! File Fingerprint: $KEY_FINGER_PRINT_FROM_FILE"
	exit 1
fi




# Create/verify the security group.

aws ec2 describe-security-groups --group-names "$SECURITY_GROUP_NAME" --output=text

# aws ec2 create-security-group --group-name "$SECURITY_GROUP_NAME" --description "$SECURITY_GROUP_DESCRIPTION"




# Facts we need to know about an environment:

# staging:
# - vpc
#    * name = @PROMPT
#    ---
#    * CIDR block = 172.42.0.0/16
#    * tenancy = default
#    ---
#    * id,
#
# - security group:
#    * name = @PROMPT
#    ---
#    * inbound ports
#       ~ http from all
#       ~ mailcatcher from all
#       ~ ssh from loadsysdev
#    * outbound ports
#       ~ all
#    ---
#    * id = (generated)
#
# - keypair
#    * name = @CONFIG
#    ---
#    * private key = (generated)
#
# - ec2 instance
#    * name = @PROMPT
#    ---
#    * ami = ami-df6a8b9b (Ubuntu 14.04 LTS, hvm, 64bit)
#    * type = t2.micro
#    * vpc id = (id from above)
#    * public ip = yes
#    * user data = script to launch provisioning
#    * ebs device (size = 15gb, type = general purpose ssd, delete on terminate = true)
#    * security group assignment = (id from above)
#    * keypair name = (name from above)
#    ---
#    * id = (generated)

