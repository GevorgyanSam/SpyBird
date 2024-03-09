<x-app title='Terms Of Service'>
    <x-slot:styles>
        <link rel="stylesheet" href="{{ asset('css/privacy/privacy.css') }}">
    </x-slot>
    <x-slot:body>
        <nav>
            <div class="navParent">
                <div class="logo">
                    <a href="{{ route('user.login') }}">
                        <x-logo color="rgb(39, 135, 245)"/>
                    </a>
                </div>
            </div>
        </nav>
        <main>
            <div class="container">
                <div class="item">
                    <div class="box">
                        <h1>SpyBird - Terms Of Service</h1>
                        <h4>Effective Date: [Insert Date]</h4>
                        <h2>introduction</h2>
                        <h4>Welcome to SpyBird, a real-time chat WEB application (the "App") provided by Samvel Gevorgyan ("we," "us," or "our"). By using the App, you agree to these Terms of Service and all applicable laws and regulations. If you do not agree with these terms, please do not use the App.</h4>
                        <h2>License and Use of the App:</h2>
                        <h4>Grant of License: Subject to these Terms of Service, you are granted a limited, non-exclusive, non-transferable, and revocable license to access and use the App for personal and non-commercial purposes.</h4>
                        <h3>Prohibited Uses: You agree not to:</h3>
                        <h4>
                            <ul>
                                <li>Violate any applicable laws, regulations, or third-party rights while using the App.</li>
                                <li>Use the App for any unlawful, harmful, or fraudulent purposes.</li>
                                <li>Attempt to gain unauthorized access to the App or its related systems.</li>
                                <li>Interfere with the proper functioning of the App or its infrastructure.</li>
                                <li>User Accounts: Some features of the App may require you to create a user account.</li>
                                <li>You are responsible for maintaining the confidentiality of your account information and all activities under your account.</li>
                            </ul>
                        </h4>
                        <h3>Intellectual Property Rights:</h3>
                        <h4>The App and its contents, including but not limited to text, images, graphics, logos, and software, are protected by intellectual property laws and are the property of Samvel Gevorgyan or its licensors. You are granted no right or license with respect to any of these materials except as explicitly provided in these Terms of Service.</h4>
                        <h3>User Content:</h3>
                        <h4>Your Content: By using the App, you may submit or share content, such as chat messages, images, and multimedia files ("User Content"). You retain ownership rights to your User Content, and by submitting it, you grant us a worldwide, royalty-free, non-exclusive, and transferable license to use, reproduce, distribute, and display your User Content to operate and improve the App.</h4>
                        <h4>Responsibility for User Content: You are solely responsible for your User Content and the consequences of sharing it through the App. Do not submit User Content that violates these Terms of Service or infringes on the rights of others.</h4>
                        <h3>Termination:</h3>
                        <h4>We may suspend or terminate your access to the App at any time for any reason, including violations of these Terms of Service or applicable laws.</h4>
                        <h3>Limitation of Liability:</h3>
                        <h4>To the extent permitted by law, we shall not be liable for any direct, indirect, incidental, special, consequential, or exemplary damages resulting from your use of the App.</h4>
                        <h3>Indemnification:</h3>
                        <h4>You agree to indemnify and hold harmless Samvel Gevorgyan and its affiliates, officers, employees, and agents from any claims, losses, damages, liabilities, and expenses (including attorney's fees) arising out of your use of the App or violation of these Terms of Service.</h4>
                        <h3>Modifications to the App and Terms of Service:</h3>
                        <h4>We reserve the right to modify, suspend, or discontinue the App or any part thereof at any time without notice. We may also revise these Terms of Service from time to time. By continuing to use the App after any such changes, you accept and agree to be bound by the updated Terms of Service.</h4>
                        <h2>Contact Us:</h2>
                        <h4>If you have any questions or concerns about these Terms of Service or the App, please contact us at <a href="mailto:{{ env('MAIL_FROM_ADDRESS') }}">{{ env('MAIL_FROM_ADDRESS') }}</a></h4>
                    </div>
                </div>
            </div>
        </main>
    </x-slot>
    <x-slot:scripts>
        <script src="{{ asset('js/privacy/privacy.js') }}"></script>
    </x-slot>
</x-app>