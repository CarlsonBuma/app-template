import { redirects } from 'src/boot/modules/globals.js';

export default [
    {
        "title": "1 - Introduction",
        "description": "These General Terms and Conditions (AGB, Schweiz) govern the use of the platform " + process.env.APP_NAME + " provided by Gigup Solutions GmbH (hereinafter referred to as 'Us'). These conditions define rights, responsibilities, and limitations for all parties engaging with our platform."
    },
    {
        title: '2 - Scope of Services',
        description: "Our platform enables users to discover locations, participate in events, connect with communities, and access location-related services. The platform serves as an intermediary between members of our community (hereinafter referred to as 'Users')."
    }, 

    // Nutzungsbedingungen
    {
        title: '3 - Terms of Use',
        description: "By accessing and using our platform, users agree to comply with these terms. Users are responsible for the accuracy of the information they provide. Users must comply with applicable laws and refrain from using the platform for unlawful activities."
    }, 
    {
        title: '3.1 - User Responsibilities',
        description: 'We do not guarantee the accuracy of user-generated content. Users acknowledge that information may be outdated, incomplete, or incorrect, and should independently verify critical details before relying on them.'
    },
    {
        title: '3.2 - Event Participation',
        description: 'Participation in events is subject to availability and the specific terms set by event organizers. We are not responsible for cancellations, modifications, or the quality of events provided by our users.'
    },
    {
        title: '3.3 - Use of External Links',
        description: 'Our app may include links to third-party websites. We do not endorse, control, or take responsibility for the content, accuracy, or privacy practices of external sites.'
    }, 

    // Intelectual Property
    {
        title: '4 - Intelectual Property',
        description: "This section outlines the intellectual property rights and responsibilities of users regarding content uploaded, stored, and shared within our platform. Users must ensure they understand the implications of public accessibility to their content."
    },
    {
        title: '4.1 - Content Visibility',
        description: "Users acknowledge that media and content they provide may be publicly accessible. By uploading content, users grant permission for their data to be displayed, processed, and reused within the platform ecosystem. We recommend users refrain from uploading sensitive or proprietary material."
    },
    {
        title: '4.2 - User-Generated Content',
        description: "By submitting content through our platform, users confirm they hold all necessary rights and permissions. Users grant " + process.env.APP_NAME + " a non-exclusive, royalty-free, worldwide license to store, process, display, and distribute uploaded content within the platform ecosystem."
    },
    {
        title: '4.3 - Intellectual Property Protection',
        description: "Users acknowledge full liability for any unauthorized use of copyrighted material. Any copyright disputes, claims, or ownership conflicts must be resolved directly between the affected parties. We do not intervene in legal disputes, nor do we actively monitor for intellectual property violations."
    },

    // Payments
    {
        title: '5 - Payments & Access',
        description: "Certain features in our platform may require payment. Payments are securely processed through third-party providers (ref. 'Use of Third-Party Services'). Access to paid services will be granted upon successful payment according to the selected product and its terms."
    }, {
        title: '5.1 - Subscriptions',
        description: 'Subscription-based services are billed periodically according to the selected plan. Upon successful payment, access will be granted automatically for the designated period. Users can cancel their subscription at any time; however, access to the service will be terminated immediatelly.'
    }, {
        title: '5.2 - Refund Policies',
        description: 'Refunds are only granted in cases of clear billing errors or technical failures directly affecting platform functionality. Fees and subscription payments are otherwise non-refundable. Payments processed through third-party providers are subject to their respective terms.'
    },


    // Third Party Usage
    {
        title: "6 - Use of Third-Party Services",
        description: "Our platform integrates or provides access to third-party services, including hosting providers, payment processors, and cloud storage solutions. By using our platform, users acknowledge that their data may be processed through these external services, subject to their respective privacy policies and terms."
    },
    {
        title: '6.1 - App Hosting',
        description: "User data and platform functionalities are hosted and processed through our infrastructure, managed by our hosting provider Firestorm, located in Switzerland (see https://firestorm.ch). Data provided by users is securely processed and distributed within our platform ecosystem in accordance with Swiss Data Law."
    },
    {
        title: '6.2 - Google Cloud Platform',
        description: "Certain features of our platform utilize services from Google Cloud Platform, including authentication via Google Login, geolocation functionalities via Google Maps API, and cloud-based data processing. Users engaging with these services agree to Google's policies. Further details can be found at https://cloud.google.com."
    },
    {
        title: '6.3 - Media Cloud Storage',
        description: 'Media files such as images, videos, and documents may be stored using third-party cloud providers like Google Cloud Platform. Uploaded content may be publicly accessible via the internet. We assume no liability for unauthorized access, data exposure, or modifications made outside our platform.'
    },
    {
        title: '6.4 - Paddle Payments',
        description: 'Certain platform features or event-related services may require payments, which are securely processed via the third-party provider Paddle. Paddle manages all payment transactions, billing, and refund policies. Users engaging in transactions through Paddle must comply with its terms, available at https://www.paddle.com.'
    },
    {
        title: '6.5 - Third Party Liablility',
        description: 'We do not control the policies, security measures, or operational procedures of third-party providers. Users acknowledge that any issues arising from third-party service use must be addressed directly with the respective provider. Content violating legal or ethical guidelines may be subject to removal without prior notice.'
    },
    
    // Data & Deletion
    {
        title: '7 - Data Privacy & Protection',
        description: 'We process personal data in compliance with the Swiss Data Protection Act (DSG Schweiz, 2023). Please refer to our Privacy Policy for detailed information on data collection, usage, and user rights.'
    }, 
    {
        title: '7.1 - Account Termination & Deletion',
        description: 'Users may delete their accounts at any time. Gigup Solutions GmbH reserves the right to suspend or terminate accounts in cases of misconduct, violation of these terms, or legal obligations.'
    }, 
    
    // Disclaimer
    {
        title: '8 - Liability & Disclaimer',
        description: 'We are not liable for user interactions, or any damages resulting from the use of our services. Users assume full responsibility for their actions on the platform.'
    },
    {
        title: '9 - Modifications & Updates',
        description: 'We reserve the right to update these terms when necessary. Continued use of the platform after modifications constitutes acceptance of the revised terms.'
    }, 
    {
        title: '10 - Governing Law & Jurisdiction',
        description: 'These terms are governed by Swiss law. Any legal disputes shall be resolved in the courts of Bern, Switzerland.'
    },
    {
        title: '11 - Contact & Support',
        description: 'For questions regarding these terms, please contact us at ' + redirects.emailLegal + ' or visit our website at www.gigup.ch.'
    }
];
