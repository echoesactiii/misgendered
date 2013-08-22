<div class="hero-unit">
	<div class="row">
		<div class="col-lg-6 visible-lg">
			<img src="/gender.png" class="hero-image">
		</div>
		<div class="col-lg-6 col-sm-12">
			<h2>Send a letter</h2>
			Kogi retro ullamco, Williamsburg YOLO intelligentsia seitan DIY viral deserunt. Fanny pack tattooed cornhole, est accusamus semiotics asymmetrical 90's ethnic intelligentsia kogi.
			<br/><br/>
            <div id="formset-initial">
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
        					<option value="misgendered">I was misgendered by an employee.</option>
        					<option value="mx">Please add Mx. to your list of titles.</option>
        					<option value="othergender">Please expand your mandatory-yet-binary gender choices.</option>
        				</select>
        			</div>
        			<br/>
        			<button type="submit" id="btn-initial-next" class="btn btn-large btn-primary">Next...</button>
        		</form>
            </div>
            <div id="formset-misgendered">
                <form>
                    <div class="hero-form col-12">
                        <select class="form-control" id="form-gender-id">
                            <option value="ftm">Female-to-male transgender</option>
                            <option value="mtf">Male-to-female transgender</option>
                            <option value="genderqueer">Genderqueer</option>
                            <option value="androgyne">Androgyne</option>
                            <option value="genderless">Genderless</option>
                            <option value="neutrois">Neutrois</option>
                            <option value="genderfluid">Gender-fluid</option>
                        </select>
                    </div>
                    <div class="hero-from col-12">
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
                            <option value="intentional">I feel as though the comment was intentional.</option>
                            <option value="unintentional">I feel as though the comment was unintentional; they didn't know better.</option>
                        </select>
                    </div>
                    <div class="hero-form col-12">
                        <textarea class="form-control" placeholder="Explanation of gender identity" id="form-gender-explanation"></textarea>
                    </div>
                    <button type="submit" class="btn btn-large btn-primary">Next...</button>
                </form>
            </div>
		</div>
	</div>
</div>