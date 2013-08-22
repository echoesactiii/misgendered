<div class="hero-unit">
	<div class="row">
		<div class="col-lg-6 visible-lg">
			<img src="/gender.png" class="hero-image">
		</div>
		<div class="col-lg-6 col-sm-12">
            <!-- Starting point for all templates -->
            <div id="formset-initial" class="formset-div">
                <h2>Send a letter</h2>
                Kogi retro ullamco, Williamsburg YOLO intelligentsia seitan DIY viral deserunt. Fanny pack tattooed cornhole, est accusamus semiotics asymmetrical 90's ethnic intelligentsia kogi.
                <br/><br/>

    			<form>
        			<div class="hero-form col-12"><input type="text" id="form-name" class="form-control" placeholder="Name of organisation" /></div>
        			<div class="hero-form col-12"><input type="text" id="form-address" class="form-control" placeholder="Street Address" /></div>
        			<div class="hero-form col-6">
        				<input type="text" class="form-control" id="form-city" placeholder="City, town, etc" />
        			</div>
        			<div class="hero-form col-6">
        				<input type="text" class="form-control" id="form-postcode" placeholder="Postcode" />
        			</div>
        			<div class="hero-form col-12">
        				<select class="form-control" id="form-template">
                            <option class="opt-placeholder">What happened?</option>
        					<option value="misgendered">I was misgendered by an employee.</option>
        					<option value="mx">Please add Mx. to your list of titles.</option>
        					<option value="othergender">Please expand your mandatory-yet-binary gender choices.</option>
        				</select>
        			</div>
        			<br/>
        			<button type="submit" id="btn-initial-next" class="btn btn-large btn-primary">Next...</button>
        		</form>
            </div>
            
            <!-- TEMPLATE: Misgendered -->
            <div id="formset-misgendered" class="formset-div">
                <h2>We need some more information...</h2>
                <form>
                    <div class="hero-form col-12">
                        <select class="form-control" id="form-gender-id">
                            <option class="opt-placeholder">What is your gender identity?</option>
                            <option value="ftm">Female-to-male transgender</option>
                            <option value="mtf">Male-to-female transgender</option>
                            <option value="genderqueer">Genderqueer</option>
                            <option value="androgyne">Androgyne</option>
                            <option value="genderless">Genderless</option>
                            <option value="neutrois">Neutrois</option>
                            <option value="genderfluid">Gender-fluid</option>
                        </select>
                    </div>
                    <div class="hero-form col-12">
                        <textarea class="form-control" placeholder="Explanation of gender identity" id="form-gender-explanation"></textarea>
                    </div>
                    <div class="hero-form col-12">
                        <p class="form-label">
                            <b>Please select all of the following that apply:</b><br/>
                            <span class="visible-md visible-lg">(Hold down control or command to select more than one)</span>
                        </p>
                        <select class="form-control" id="form-mistake" multiple="true">
                            <option value="misgendered">I was misgendered</option>
                            <option value="pronouns">The pronouns used were incorrect</option>
                            <option value="sir-madam">They called me sir/madam without forethought</option>
                            <option value="third-person">Described me to another individual incorrectly as a man/woman</option>
                            <option value="third-person-object">Described me to another individual referring to me as an object (e.g. 'that' or 'it')</option>
                        </select>
                    </div>
                    <div class="hero-form col-12">
                        <select class="form-control" id="form-intent">
                            <option class="opt-placeholder">How do you feel the comment was intended?</option>
                            <option value="intentional">I feel as though the comment was intentional.</option>
                            <option value="unintentional">I feel as though the comment was unintentional; they didn't know better.</option>
                        </select>
                    </div>
                    <button type="submit" id="btn-final-next" class="btn btn-large btn-primary">Next...</button>
                </form>
            </div>

            <!-- Ending point for all templates -->
            <div id="formset-final" class="formset-div">
                <h2>And this is all optional!</h2>
                <form>
                    <div class="hero-form col-12"><input type="text" id="form-sender-name" class="form-control" placeholder="Your name (optional)" /></div>
                    <div class="hero-form col-12"><textarea class="form-control" placeholder="Optionally, please enter a short paragraph of text to be ended to the end of the letter that details who, when and where this incident occurred." id="form-sender-details"></textarea></div>
                    <div class="hero-form col-12"><textarea class="form-control" placeholder="Optionally, please enter an address, email address or phone contact details if you would like a response." id="form-sender-contact"></textarea></div>
                    <button type="submit" id="btn-send" class="btn btn-large btn-primary">Send!</button>
                </form>
            </div>
		</div>
	</div>
</div>